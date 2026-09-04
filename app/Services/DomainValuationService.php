<?php

namespace App\Services;

class DomainValuationService
{
    protected ClickHouseService $clickhouse;

    public function __construct(ClickHouseService $clickhouse)
    {
        $this->clickhouse = $clickhouse;
    }

    public function valueDomain(string $domainName): array
    {
        if (!$this->clickhouse->isConnected()) {
            return $this->result(false, 'ClickHouse is not connected.');
        }

        $parsed = $this->parseDomain($domainName);
        if (!$parsed) {
            return $this->result(false, 'This tool only analyzes GEO domains (a city/state + keyword + TLD). Please enter a domain like "losangeleshomes.com" or "floridahousesales.com".');
        }

        $location = $parsed['location'];
        $locationKey = $parsed['locationKey'];
        $keyword = $parsed['keyword'];
        $tld = $parsed['tld'];
        $geoType = $parsed['geo_type'];

        $locationSales = $this->locationSalesCount($locationKey, $geoType);
        $keywordSales = $this->keywordSalesCount($keyword);
        $exactMatches = $this->exactMatchCount($locationKey, $keyword, $geoType);

        $popRow = $this->locationPopulation($locationKey, $geoType);
        $population = $popRow['population'];

        $hasSalesData = $locationSales > 0 || $keywordSales > 0;
        $hasPopulation = $population > 0;

        if (!$hasSalesData && !$hasPopulation) {
            return $this->result(
                false,
                'No data yet. We have no past sales for this location/keyword and no population record. Add past sales and population data to get an analysis.',
                [
                    'domain_name' => $domainName,
                    'location' => $location,
                    'geo_type' => $geoType,
                    'keyword' => $keyword,
                    'tld' => $tld,
                    'location_sales' => 0,
                    'keyword_sales' => 0,
                    'exact_matches' => 0,
                    'population' => 0,
                    'has_population' => false,
                ]
            );
        }

        $salesScore = $this->salesScore($locationSales, $keywordSales);
        $popScore = $this->populationScore($population, $geoType);
        $exactBonus = min(20, $exactMatches * 7);
        $chance = min(96, $salesScore + $popScore + $exactBonus);

        $estimate = $this->estimateFromComparables($locationKey, $keyword, $geoType);
        $comparables = $this->comparableSales($locationKey, $keyword, $geoType);

        $rating = $geoType === 'state'
            ? $this->stateRatingLabel($population, $hasPopulation)
            : $this->cityRatingLabel($population, $hasPopulation);

        return $this->result(
            true,
            'Analysis complete.',
            [
                'domain_name' => $domainName,
                'location' => $location,
                'geo_type' => $geoType,
                'keyword' => $keyword,
                'tld' => $tld,
                'location_sales' => $locationSales,
                'keyword_sales' => $keywordSales,
                'exact_matches' => $exactMatches,
                'population' => $population,
                'has_population' => $hasPopulation,
                'city_rating' => $rating,
                'keyword_demand' => $this->keywordDemandLabel($keywordSales),
                'chance' => $chance,
                'estimated_value' => round($estimate['median'], 0),
                'estimate_low' => round($estimate['low'], 0) > 0 ? round($estimate['low'], 0) : null,
                'estimate_high' => round($estimate['high'], 0) > 0 ? round($estimate['high'], 0) : null,
                'has_estimate' => $estimate['median'] > 0,
                'comparables' => $comparables,
                'note' => $this->buildNote($location, $keyword, $locationSales, $keywordSales, $exactMatches, $population, $hasPopulation, $geoType),
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

        $knownCities = $this->knownCityLookup();
        $knownStates = $this->knownStateLookup();

        $best = null;
        $len = strlen($base);

        $register = function (string $locPart, string $kwPart, string $displayName, string $geoType) use (&$best) {
            if (strlen($locPart) < 2 || strlen($kwPart) < 3) return;
            $keywordSales = $this->keywordSalesCount($kwPart);
            $score = ($geoType === 'state' ? 70 : 60) + strlen($locPart);
            if ($keywordSales > 0) $score += 30;

            if ($best === null || $score > $best['score']) {
                $best = [
                    'location' => $displayName,
                    'locationKey' => $locPart,
                    'keyword' => $kwPart,
                    'score' => $score,
                    'geo_type' => $geoType,
                ];
            }
        };

        for ($i = 1; $i < $len; $i++) {
            $head = substr($base, 0, $i);
            $tail = substr($base, $i);

            if (isset($knownCities[$head])) $register($head, $tail, $knownCities[$head], 'city');
            if (isset($knownStates[$head])) $register($head, $tail, $knownStates[$head], 'state');

            if (isset($knownCities[$tail])) $register($tail, $head, $knownCities[$tail], 'city');
            if (isset($knownStates[$tail])) $register($tail, $head, $knownStates[$tail], 'state');
        }

        if (!$best) return null;

        return [
            'location' => $best['location'],
            'locationKey' => $best['locationKey'],
            'keyword' => $best['keyword'],
            'tld' => $tld,
            'geo_type' => $best['geo_type'],
        ];
    }

    protected function knownCityLookup(): array
    {
        if (!$this->clickhouse->isConnected()) return [];
        $cacheKey = 'cando_known_cities_v3';
        $cache = \Illuminate\Support\Facades\Cache::get($cacheKey, []);
        if (!empty($cache)) return $cache;

        $map = [];
        $stmt = $this->clickhouse->query("SELECT DISTINCT city FROM domain_sales WHERE city != ''");
        if ($stmt) {
            foreach ($stmt->rows() as $row) {
                $norm = preg_replace('/[^a-z]/', '', strtolower($row['city']));
                if ($norm !== '') $map[$norm] = $row['city'];
            }
        }
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

    protected function knownStateLookup(): array
    {
        if (!$this->clickhouse->isConnected()) return [];
        $cacheKey = 'cando_known_states_v1';
        $cache = \Illuminate\Support\Facades\Cache::get($cacheKey, []);
        if (!empty($cache)) return $cache;

        $map = [];
        $stmt = $this->clickhouse->query("SELECT DISTINCT state FROM domain_sales WHERE state != ''");
        if ($stmt) {
            foreach ($stmt->rows() as $row) {
                $norm = preg_replace('/[^a-z]/', '', strtolower($row['state']));
                if ($norm !== '' && strlen($norm) >= 3) $map[$norm] = $row['state'];
            }
        }
        $stmt2 = $this->clickhouse->query("SELECT DISTINCT state FROM city_population WHERE state != ''");
        if ($stmt2) {
            foreach ($stmt2->rows() as $row) {
                $norm = preg_replace('/[^a-z]/', '', strtolower($row['state']));
                if ($norm !== '' && strlen($norm) >= 3 && !isset($map[$norm])) $map[$norm] = $row['state'];
            }
        }
        $states = [
            'alabama' => 'Alabama', 'alaska' => 'Alaska', 'arizona' => 'Arizona',
            'arkansas' => 'Arkansas', 'california' => 'California', 'colorado' => 'Colorado',
            'connecticut' => 'Connecticut', 'delaware' => 'Delaware', 'florida' => 'Florida',
            'georgia' => 'Georgia', 'hawaii' => 'Hawaii', 'idaho' => 'Idaho',
            'illinois' => 'Illinois', 'indiana' => 'Indiana', 'iowa' => 'Iowa',
            'kansas' => 'Kansas', 'kentucky' => 'Kentucky', 'louisiana' => 'Louisiana',
            'maine' => 'Maine', 'maryland' => 'Maryland', 'massachusetts' => 'Massachusetts',
            'michigan' => 'Michigan', 'minnesota' => 'Minnesota', 'mississippi' => 'Mississippi',
            'missouri' => 'Missouri', 'montana' => 'Montana', 'nebraska' => 'Nebraska',
            'nevada' => 'Nevada', 'newhampshire' => 'New Hampshire', 'newjersey' => 'New Jersey',
            'newmexico' => 'New Mexico', 'newyork' => 'New York', 'northcarolina' => 'North Carolina',
            'northdakota' => 'North Dakota', 'ohio' => 'Ohio', 'oklahoma' => 'Oklahoma',
            'oregon' => 'Oregon', 'pennsylvania' => 'Pennsylvania', 'rhodeisland' => 'Rhode Island',
            'southcarolina' => 'South Carolina', 'southdakota' => 'South Dakota', 'tennessee' => 'Tennessee',
            'texas' => 'Texas', 'utah' => 'Utah', 'vermont' => 'Vermont',
            'virginia' => 'Virginia', 'washington' => 'Washington', 'westvirginia' => 'West Virginia',
            'wisconsin' => 'Wisconsin', 'wyoming' => 'Wyoming',
            'ontario' => 'Ontario', 'quebec' => 'Quebec', 'britishcolumbia' => 'British Columbia',
            'alberta' => 'Alberta', 'manitoba' => 'Manitoba', 'saskatchewan' => 'Saskatchewan',
            'nova' => 'Nova Scotia',
            'victoria' => 'Victoria', 'newsouthwales' => 'New South Wales', 'queensland' => 'Queensland',
            'southaustralia' => 'South Australia', 'westernaustralia' => 'Western Australia',
            'texas' => 'Texas',
        ];
        foreach ($states as $norm => $full) {
            if (!isset($map[$norm])) $map[$norm] = $full;
        }
        if (!empty($map)) {
            \Illuminate\Support\Facades\Cache::put($cacheKey, $map, 300);
        }
        return $map;
    }

    // ==================== PAST SALES DATA ====================

    protected function locationSalesCount(string $locationKey, string $geoType): int
    {
        if (!$this->clickhouse->isConnected()) return 0;

        if ($geoType === 'state') {
            $stmt = $this->clickhouse->query(
                "SELECT count() as c FROM domain_sales WHERE replaceAll(lower(state), ' ', '') = :loc",
                ['loc' => strtolower($locationKey)]
            );
        } else {
            $stmt = $this->clickhouse->query(
                "SELECT count() as c FROM domain_sales WHERE replaceAll(lower(city), ' ', '') = :loc",
                ['loc' => strtolower($locationKey)]
            );
        }
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

    protected function exactMatchCount(string $locationKey, string $keyword, string $geoType): int
    {
        if (!$this->clickhouse->isConnected() || $keyword === '') return 0;

        if ($geoType === 'state') {
            $stmt = $this->clickhouse->query(
                "SELECT count() as c FROM domain_sales WHERE replaceAll(lower(state), ' ', '') = :loc AND (lower(keyword) = :kw OR lower(domain_name) LIKE :like)",
                ['loc' => strtolower($locationKey), 'kw' => strtolower($keyword), 'like' => '%' . strtolower($keyword) . '%']
            );
        } else {
            $stmt = $this->clickhouse->query(
                "SELECT count() as c FROM domain_sales WHERE replaceAll(lower(city), ' ', '') = :loc AND (lower(keyword) = :kw OR lower(domain_name) LIKE :like)",
                ['loc' => strtolower($locationKey), 'kw' => strtolower($keyword), 'like' => '%' . strtolower($keyword) . '%']
            );
        }
        return $stmt ? (int) $stmt->fetchOne('c') : 0;
    }

    protected function estimateFromComparables(string $locationKey, string $keyword, string $geoType): array
    {
        if (!$this->clickhouse->isConnected()) return ['low' => 0, 'high' => 0, 'median' => 0];

        if ($geoType === 'state') {
            $stmt = $this->clickhouse->query(
                "SELECT price FROM domain_sales WHERE replaceAll(lower(state), ' ', '') = :loc AND (lower(keyword) = :kw OR lower(domain_name) LIKE :like) LIMIT 100",
                ['loc' => strtolower($locationKey), 'kw' => strtolower($keyword), 'like' => '%' . strtolower($keyword) . '%']
            );
        } else {
            $stmt = $this->clickhouse->query(
                "SELECT price FROM domain_sales WHERE replaceAll(lower(city), ' ', '') = :loc AND (lower(keyword) = :kw OR lower(domain_name) LIKE :like) LIMIT 100",
                ['loc' => strtolower($locationKey), 'kw' => strtolower($keyword), 'like' => '%' . strtolower($keyword) . '%']
            );
        }
        if ($stmt) {
            $prices = array_map('floatval', array_column($stmt->rows(), 'price'));
            if (!empty($prices)) return $this->priceStats($prices);
        }

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

    protected function comparableSales(string $locationKey, string $keyword, string $geoType): array
    {
        if (!$this->clickhouse->isConnected() || $keyword === '') return [];

        if ($geoType === 'state') {
            $stmt = $this->clickhouse->query(
                "SELECT domain_name, price, city, keyword FROM domain_sales WHERE replaceAll(lower(state), ' ', '') = :loc AND (lower(keyword) = :kw OR lower(domain_name) LIKE :like) LIMIT 50",
                ['loc' => strtolower($locationKey), 'kw' => strtolower($keyword), 'like' => '%' . strtolower($keyword) . '%']
            );
        } else {
            $stmt = $this->clickhouse->query(
                "SELECT domain_name, price, city, keyword FROM domain_sales WHERE replaceAll(lower(city), ' ', '') = :loc AND (lower(keyword) = :kw OR lower(domain_name) LIKE :like) LIMIT 50",
                ['loc' => strtolower($locationKey), 'kw' => strtolower($keyword), 'like' => '%' . strtolower($keyword) . '%']
            );
        }
        $rows = $stmt ? $stmt->rows() : [];

        if (empty($rows)) {
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

    // ==================== POPULATION TABLE ====================

    protected function locationPopulation(string $locationKey, string $geoType): array
    {
        if (!$this->clickhouse->isConnected()) return ['population' => 0, 'city' => '', 'state' => ''];

        if ($geoType === 'state') {
            $stmt = $this->clickhouse->query(
                "SELECT state, any(population) as pop FROM city_population WHERE replaceAll(lower(state), ' ', '') = :loc GROUP BY state LIMIT 1",
                ['loc' => strtolower($locationKey)]
            );
        } else {
            $stmt = $this->clickhouse->query(
                "SELECT city, state, any(population) as pop FROM city_population WHERE replaceAll(lower(city), ' ', '') = :loc GROUP BY city, state LIMIT 1",
                ['loc' => strtolower($locationKey)]
            );
        }
        if (!$stmt) return ['population' => 0, 'city' => '', 'state' => ''];
        $row = $stmt->fetchOne();
        return [
            'population' => (int) ($row['pop'] ?? 0),
            'city' => $row['city'] ?? '',
            'state' => $row['state'] ?? '',
        ];
    }

    // ==================== SCORING ====================

    protected function salesScore(int $locationSales, int $keywordSales): int
    {
        $score = 0;
        if ($locationSales >= 5) $score += 25;
        elseif ($locationSales >= 3) $score += 18;
        elseif ($locationSales >= 1) $score += 10;

        if ($keywordSales >= 10) $score += 30;
        elseif ($keywordSales >= 5) $score += 22;
        elseif ($keywordSales >= 3) $score += 14;
        elseif ($keywordSales >= 1) $score += 6;

        return min(55, $score);
    }

    protected function populationScore(int $population, string $geoType = 'city'): int
    {
        if ($geoType === 'state') {
            if ($population >= 20000000) return 40;
            if ($population >= 15000000) return 36;
            if ($population >= 10000000) return 32;
            if ($population >= 5000000) return 27;
            if ($population >= 2000000) return 20;
            if ($population >= 1000000) return 13;
            return 8;
        }

        if ($population >= 10000000) return 40;
        if ($population >= 5000000) return 34;
        if ($population >= 1000000) return 27;
        if ($population >= 500000) return 20;
        if ($population >= 100000) return 13;
        if ($population >= 50000) return 8;
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

    protected function stateRatingLabel(int $population, bool $hasPopulation): string
    {
        if (!$hasPopulation) return 'No population record';
        if ($population >= 20000000) return 'Mega State';
        if ($population >= 15000000) return 'Very Large State';
        if ($population >= 10000000) return 'Large State';
        if ($population >= 5000000) return 'Medium State';
        if ($population >= 2000000) return 'Small State';
        if ($population >= 1000000) return 'Tiny State';
        return 'Small State';
    }

    protected function keywordDemandLabel(int $keywordSales): string
    {
        if ($keywordSales >= 10) return 'Very High Demand';
        if ($keywordSales >= 5) return 'High Demand';
        if ($keywordSales >= 3) return 'Good Demand';
        if ($keywordSales >= 1) return 'Low Demand';
        return 'No sales yet';
    }

    protected function buildNote(string $location, string $keyword, int $locationSales, int $keywordSales, int $exactMatches, int $population, bool $hasPopulation, string $geoType): string
    {
        $parts = [];
        $locType = $geoType === 'state' ? 'State' : 'City';

        if ($hasPopulation) {
            $parts[] = $location . ' has a population of ~' . number_format($population) .
                ' (' . ($geoType === 'state' ? $this->stateRatingLabel($population, true) : $this->cityRatingLabel($population, true)) . '). A bigger ' . strtolower($locType) . ' means more buyers, so a higher chance this domain sells.';
        } else {
            $parts[] = $location . ' has no population record yet - add it to the population table for better analysis.';
        }

        $parts[] = 'The keyword "' . $keyword . '" has sold ' . $keywordSales . ' time(s) in past sales.';
        if ($locationSales > 0) {
            $parts[] = 'Domains for ' . $location . ' have sold ' . $locationSales . ' time(s) in the past.';
        } else {
            $parts[] = 'No past sales recorded for ' . $location . ' yet.';
        }
        if ($exactMatches > 0) {
            $parts[] = 'This exact ' . strtolower($locType) . ' + keyword combo has ' . $exactMatches . ' past sale(s).';
        }

        return implode(' ', $parts);
    }

    protected function result(bool $success, string $message, array $data = []): array
    {
        return array_merge([
            'success' => $success,
            'message' => $message,
            'is_geo' => true,
            'warning' => 'This tool ONLY analyzes GEO domains. It uses our PAST SALES data and our population table. Everything is an ESTIMATE, NOT guaranteed.',
        ], $data);
    }
}
