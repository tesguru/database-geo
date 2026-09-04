<?php

namespace App\Services;

class DomainValuationService
{
    protected ClickHouseService $clickhouse;

    public function __construct(ClickHouseService $clickhouse)
    {
        $this->clickhouse = $clickhouse;
    }

    /**
     * Value a geo-domain by parsing the domain itself.
     *
     * Flow:
     *  1. Parse domain -> city + keyword + tld
     *  2. Check PAST SALES data: how many times city sold, how many times keyword sold
     *  3. Check CITY POPULATION table: the city's population
     *  4. Score: past sales demand + population (big city boosts chance)
     */
    public function valueDomain(string $domainName): array
    {
        if (!$this->clickhouse->isConnected()) {
            return $this->result(false, 'ClickHouse is not connected.');
        }

        $parsed = $this->parseDomain($domainName);
        if (!$parsed) {
            return $this->result(false, 'This tool only analyzes GEO domains (a city + keyword + TLD). Please enter a domain like "losangeleshomes.com".');
        }

        $city = $parsed['city'];          // display name, e.g. "Los Angeles"
        $cityKey = $parsed['cityKey'];    // normalized, e.g. "losangeles"
        $keyword = $parsed['keyword'];
        $tld = $parsed['tld'];

        // ---- 1. PAST SALES DATA ----
        $citySales = $this->citySalesCount($cityKey);
        $keywordSales = $this->keywordSalesCount($keyword);
        $exactMatches = $this->exactMatchCount($cityKey, $keyword);

        // ---- 2. CITY POPULATION TABLE ----
        $popRow = $this->cityPopulation($cityKey);
        $population = $popRow['population'];

        $hasSalesData = $citySales > 0 || $keywordSales > 0;
        $hasPopulation = $population > 0;

        if (!$hasSalesData && !$hasPopulation) {
            return $this->result(
                false,
                'No data yet. We have no past sales for this city/keyword and no population record. Add past sales and city population to get an analysis.',
                [
                    'domain_name' => $domainName,
                    'city' => $city,
                    'keyword' => $keyword,
                    'tld' => $tld,
                    'city_sales' => 0,
                    'keyword_sales' => 0,
                    'exact_matches' => 0,
                    'population' => 0,
                    'has_population' => false,
                ]
            );
        }

        // ---- 3. SCORING ----
        // Past sales demand (how many times it sold)
        $salesScore = $this->salesScore($citySales, $keywordSales);

        // Population score (big city = more buyers = higher chance to sell)
        $popScore = $this->populationScore($population);

        // Exact match bonus
        $exactBonus = min(20, $exactMatches * 7);

        $chance = min(96, $salesScore + $popScore + $exactBonus);

        // Estimated value from comparable past sales (city + keyword)
        $estimate = $this->estimateFromComparables($cityKey, $keyword);

        // Actual domains seen in past data for this city/keyword
        $comparables = $this->comparableSales($cityKey, $keyword);

        return $this->result(
            true,
            'Analysis complete.',
            [
                'domain_name' => $domainName,
                'city' => $city,
                'keyword' => $keyword,
                'tld' => $tld,
                'city_sales' => $citySales,
                'keyword_sales' => $keywordSales,
                'exact_matches' => $exactMatches,
                'population' => $population,
                'has_population' => $hasPopulation,
                'city_rating' => $this->cityRatingLabel($population, $hasPopulation),
                'keyword_demand' => $this->keywordDemandLabel($keywordSales),
                'chance' => $chance,
                'estimated_value' => round($estimate['median'], 0),
                'estimate_low' => round($estimate['low'], 0) > 0 ? round($estimate['low'], 0) : null,
                'estimate_high' => round($estimate['high'], 0) > 0 ? round($estimate['high'], 0) : null,
                'has_estimate' => $estimate['median'] > 0,
                'comparables' => $comparables,
                'note' => $this->buildNote($city, $keyword, $citySales, $keywordSales, $exactMatches, $population, $hasPopulation),
            ]
        );
    }

    public function valueMany(array $rows): array
    {
        $out = [];
        foreach ($rows as $row) {
            $out[$row['id']] = $this->valueDomain($row['domain_name'] ?? '');
        }
        return $out;
    }

    // ==================== DOMAIN PARSING ====================

    protected function parseDomain(string $domain): ?array
    {
        $domain = strtolower(trim($domain));
        $domain = preg_replace('#^https?://#', '', $domain);
        $domain = preg_replace('#^www\.#', '', $domain);

        if (!preg_match('/\.([a-z]{2,})([.\/].*)?$/', $domain, $m)) {
            return null;
        }
        $tld = '.' . $m[1];
        $base = explode('.', $domain)[0];
        $base = preg_replace('/[^a-z]/', '', $base);
        if ($base === '') return null;

        // Build known city lookup from BOTH domain_sales.city and city_population.city
        $knownCities = $this->knownCityLookup();
        if (empty($knownCities)) return null;

        $best = null;
        $len = strlen($base);
        for ($i = 1; $i < $len; $i++) {
            $cityPart = substr($base, 0, $i);
            $keywordPart = substr($base, $i);
            if (strlen($cityPart) < 3 || strlen($keywordPart) < 3) continue;

            if (isset($knownCities[$cityPart])) {
                $cityDisplay = $knownCities[$cityPart];
                $keywordSales = $this->keywordSalesCount($keywordPart);
                $score = 60 + strlen($cityPart);
                if ($keywordSales > 0) $score += 30;

                if ($best === null || $score > $best['score']) {
                    $best = ['city' => $cityDisplay, 'cityKey' => $cityPart, 'keyword' => $keywordPart, 'score' => $score];
                }
            }
        }

        if (!$best) return null;

        return [
            'city' => $best['city'],
            'cityKey' => $best['cityKey'],
            'keyword' => $best['keyword'],
            'tld' => $tld,
        ];
    }

    protected function knownCityLookup(): array
    {
        if (!$this->clickhouse->isConnected()) return [];
        $cacheKey = 'cando_known_cities_v2';
        $cache = \Illuminate\Support\Facades\Cache::get($cacheKey, []);
        if (!empty($cache)) return $cache;

        $map = [];
        // From past sales
        $stmt = $this->clickhouse->query("SELECT DISTINCT city FROM domain_sales WHERE city != ''");
        if ($stmt) {
            foreach ($stmt->rows() as $row) {
                $norm = preg_replace('/[^a-z]/', '', strtolower($row['city']));
                if ($norm !== '') $map[$norm] = $row['city'];
            }
        }
        // From city population table
        $stmt2 = $this->clickhouse->query("SELECT DISTINCT city FROM city_population WHERE city != ''");
        if ($stmt2) {
            foreach ($stmt2->rows() as $row) {
                $norm = preg_replace('/[^a-z]/', '', strtolower($row['city']));
                if ($norm !== '' && !isset($map[$norm])) $map[$norm] = $row['city'];
            }
        }
        if (!empty($map)) {
            \Illuminate\Support\Facades\Cache::put($cacheKey, $map, 300);
        }
        return $map;
    }

    // ==================== PAST SALES DATA ====================

    protected function citySalesCount(string $cityKey): int
    {
        if (!$this->clickhouse->isConnected()) return 0;
        $stmt = $this->clickhouse->query(
            "SELECT count() as c FROM domain_sales WHERE replaceAll(lower(city), ' ', '') = :city",
            ['city' => strtolower($cityKey)]
        );
        return $stmt ? (int) $stmt->fetchOne('c') : 0;
    }

    protected function keywordSalesCount(string $keyword): int
    {
        if (!$this->clickhouse->isConnected() || $keyword === '') return 0;
        $stmt = $this->clickhouse->query(
            "SELECT count() as c FROM domain_sales WHERE lower(keyword) = :kw OR lower(domain_name) LIKE :like",
            ['kw' => strtolower($keyword), 'like' => '%' . strtolower($keyword) . '%']
        );
        return $stmt ? (int) $stmt->fetchOne('c') : 0;
    }

    protected function exactMatchCount(string $cityKey, string $keyword): int
    {
        if (!$this->clickhouse->isConnected() || $keyword === '') return 0;
        $stmt = $this->clickhouse->query(
            "SELECT count() as c FROM domain_sales WHERE replaceAll(lower(city), ' ', '') = :city AND (lower(keyword) = :kw OR lower(domain_name) LIKE :like)",
            ['city' => strtolower($cityKey), 'kw' => strtolower($keyword), 'like' => '%' . strtolower($keyword) . '%']
        );
        return $stmt ? (int) $stmt->fetchOne('c') : 0;
    }

    protected function estimateFromComparables(string $cityKey, string $keyword): array
    {
        if (!$this->clickhouse->isConnected()) return ['low' => 0, 'high' => 0, 'median' => 0];

        $stmt = $this->clickhouse->query(
            "SELECT price FROM domain_sales WHERE replaceAll(lower(city), ' ', '') = :city AND (lower(keyword) = :kw OR lower(domain_name) LIKE :like) LIMIT 100",
            ['city' => strtolower($cityKey), 'kw' => strtolower($keyword), 'like' => '%' . strtolower($keyword) . '%']
        );
        if ($stmt) {
            $prices = array_map('floatval', array_column($stmt->rows(), 'price'));
            if (!empty($prices)) return $this->priceStats($prices);
        }
        // fallback: keyword-only
        $stmt2 = $this->clickhouse->query(
            "SELECT price FROM domain_sales WHERE lower(keyword) = :kw OR lower(domain_name) LIKE :like LIMIT 100",
            ['kw' => strtolower($keyword), 'like' => '%' . strtolower($keyword) . '%']
        );
        if ($stmt2) {
            $prices = array_map('floatval', array_column($stmt2->rows(), 'price'));
            if (!empty($prices)) return $this->priceStats($prices);
        }
        return ['low' => 0, 'high' => 0, 'median' => 0];
    }

    protected function comparableSales(string $cityKey, string $keyword): array
    {
        if (!$this->clickhouse->isConnected() || $keyword === '') return [];

        // Exact city + keyword matches first
        $stmt = $this->clickhouse->query(
            "SELECT domain_name, price, city, keyword FROM domain_sales WHERE replaceAll(lower(city), ' ', '') = :city AND (lower(keyword) = :kw OR lower(domain_name) LIKE :like) LIMIT 50",
            ['city' => strtolower($cityKey), 'kw' => strtolower($keyword), 'like' => '%' . strtolower($keyword) . '%']
        );
        $rows = $stmt ? $stmt->rows() : [];

        if (empty($rows)) {
            // Fallback: keyword-only matches from past data
            $stmt2 = $this->clickhouse->query(
                "SELECT domain_name, price, city, keyword FROM domain_sales WHERE lower(keyword) = :kw OR lower(domain_name) LIKE :like LIMIT 50",
                ['kw' => strtolower($keyword), 'like' => '%' . strtolower($keyword) . '%']
            );
            $rows = $stmt2 ? $stmt2->rows() : [];
        }

        $out = [];
        foreach ($rows as $row) {
            $out[] = [
                'domain_name' => $row['domain_name'] ?? '',
                'price' => (float) ($row['price'] ?? 0),
                'city' => $row['city'] ?? '',
                'keyword' => $row['keyword'] ?? '',
            ];
        }
        return $out;
    }

    protected function priceStats(array $prices): array
    {
        sort($prices);
        $count = count($prices);
        $mid = intdiv($count, 2);
        $median = $count % 2 === 0 ? ($prices[$mid - 1] + $prices[$mid]) / 2 : $prices[$mid];
        return ['low' => $prices[0], 'high' => $prices[$count - 1], 'median' => $median];
    }

    // ==================== CITY POPULATION TABLE ====================

    protected function cityPopulation(string $cityKey): array
    {
        if (!$this->clickhouse->isConnected()) return ['population' => 0, 'city' => '', 'state' => ''];
        $stmt = $this->clickhouse->query(
            "SELECT city, state, any(population) as pop FROM city_population WHERE replaceAll(lower(city), ' ', '') = :city GROUP BY city, state LIMIT 1",
            ['city' => strtolower($cityKey)]
        );
        if (!$stmt) return ['population' => 0, 'city' => '', 'state' => ''];
        $row = $stmt->fetchOne();
        return [
            'population' => (int) ($row['pop'] ?? 0),
            'city' => $row['city'] ?? '',
            'state' => $row['state'] ?? '',
        ];
    }

    // ==================== SCORING ====================

    protected function salesScore(int $citySales, int $keywordSales): int
    {
        // Past sales demand. City sales = proven local demand; keyword = proven industry demand.
        $score = 0;
        if ($citySales >= 3) $score += 25;
        elseif ($citySales >= 1) $score += 12;

        if ($keywordSales >= 10) $score += 30;
        elseif ($keywordSales >= 5) $score += 22;
        elseif ($keywordSales >= 3) $score += 14;
        elseif ($keywordSales >= 1) $score += 6;

        return min(55, $score);
    }

    protected function populationScore(int $population): int
    {
        if ($population >= 10000000) return 40;   // mega metro
        if ($population >= 5000000) return 34;
        if ($population >= 1000000) return 27;    // big city
        if ($population >= 500000) return 20;
        if ($population >= 100000) return 13;     // small city
        if ($population >= 50000) return 8;       // town
        return 0;
    }

    protected function cityRatingLabel(int $population, bool $hasPopulation): string
    {
        if (!$hasPopulation) return 'No population record';
        if ($population >= 10000000) return 'Mega Metro';
        if ($population >= 5000000) return 'Very Big City';
        if ($population >= 1000000) return 'Big City';
        if ($population >= 500000) return 'Medium City';
        if ($population >= 100000) return 'Small City';
        if ($population >= 50000) return 'Town';
        return 'Small Town';
    }

    protected function keywordDemandLabel(int $keywordSales): string
    {
        if ($keywordSales >= 10) return 'Very High Demand';
        if ($keywordSales >= 5) return 'High Demand';
        if ($keywordSales >= 3) return 'Good Demand';
        if ($keywordSales >= 1) return 'Low Demand';
        return 'No sales yet';
    }

    protected function buildNote(string $city, string $keyword, int $citySales, int $keywordSales, int $exactMatches, int $population, bool $hasPopulation): string
    {
        $parts = [];

        // City / population
        if ($hasPopulation) {
            $parts[] = $city . ' has a population of ~' . number_format($population) .
                ' (' . $this->cityRatingLabel($population, true) . '). A bigger city means more buyers, so a higher chance this domain sells.';
        } else {
            $parts[] = $city . ' has no population record yet - add it to the city population table for better analysis.';
        }

        // Keyword / past sales
        $parts[] = 'The keyword "' . $keyword . '" has sold ' . $keywordSales . ' time(s) in past sales.';
        if ($citySales > 0) {
            $parts[] = 'Domains for ' . $city . ' have sold ' . $citySales . ' time(s) in the past.';
        } else {
            $parts[] = 'No past sales recorded for ' . $city . ' yet.';
        }
        if ($exactMatches > 0) {
            $parts[] = 'This exact city + keyword combo has ' . $exactMatches . ' past sale(s).';
        }

        return implode(' ', $parts);
    }

    protected function result(bool $success, string $message, array $data = []): array
    {
        return array_merge([
            'success' => $success,
            'message' => $message,
            'is_geo' => true,
            'warning' => 'This tool ONLY analyzes GEO domains. It uses our PAST SALES data and our CITY POPULATION table. Everything is an ESTIMATE, NOT guaranteed.',
        ], $data);
    }
}
