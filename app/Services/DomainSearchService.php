<?php

namespace App\Services;

class DomainSearchService
{
    protected ClickHouseService $clickhouse;

    public function __construct(ClickHouseService $clickhouse)
    {
        $this->clickhouse = $clickhouse;
    }

    public function isConnected(): bool
    {
        return $this->clickhouse->isConnected();
    }

    public function search(array $filters = [], int $page = 1, int $perPage = 20, string $sortBy = 'price', string $sortDir = 'desc'): array
    {
        if (!$this->clickhouse->isConnected()) {
            return ['data' => [], 'total' => 0, 'page' => 1, 'per_page' => $perPage, 'last_page' => 0];
        }

        $conditions = [];
        $params = [];

        if (!empty($filters['keyword'])) {
            $conditions[] = "(lower(domain_name) LIKE :kw OR lower(keyword) LIKE :kw2 OR lower(city) LIKE :kw3 OR lower(state) LIKE :kw4 OR lower(country) LIKE :kw5)";
            $kw = '%' . strtolower($filters['keyword']) . '%';
            $params['kw'] = $kw;
            $params['kw2'] = $kw;
            $params['kw3'] = $kw;
            $params['kw4'] = $kw;
            $params['kw5'] = $kw;
        }

        if (!empty($filters['city'])) {
            $conditions[] = "lower(city) LIKE :city";
            $params['city'] = '%' . strtolower($filters['city']) . '%';
        }

        if (!empty($filters['state'])) {
            $conditions[] = "lower(state) LIKE :state";
            $params['state'] = '%' . strtolower($filters['state']) . '%';
        }

        if (!empty($filters['country'])) {
            $conditions[] = "lower(country) LIKE :country";
            $params['country'] = '%' . strtolower($filters['country']) . '%';
        }

        if (!empty($filters['min_price'])) {
            $conditions[] = "price >= :min_price";
            $params['min_price'] = (float) $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $conditions[] = "price <= :max_price";
            $params['max_price'] = (float) $filters['max_price'];
        }

        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $allowedSorts = ['price', 'domain_name', 'city', 'state', 'country', 'id'];
        $sortBy = in_array($sortBy, $allowedSorts) ? $sortBy : 'price';
        $sortDir = strtolower($sortDir) === 'asc' ? 'ASC' : 'DESC';

        $countSql = "SELECT count() as total FROM domain_sales {$whereClause}";
        $countResult = $this->clickhouse->query($countSql, $params);
        $total = $countResult ? $countResult->fetchOne('total') : 0;

        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM domain_sales {$whereClause} ORDER BY id {$sortDir} LIMIT :limit OFFSET :offset";
        $params['limit'] = $perPage;
        $params['offset'] = $offset;

        $result = $this->clickhouse->query($sql, $params);
        $rows = $result ? $result->rows() : [];

        return [
            'data' => $rows,
            'total' => (int) $total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => (int) ceil($total / $perPage),
        ];
    }

    public function searchAll(array $filters = [], int $maxResults = 10000): array
    {
        if (!$this->clickhouse->isConnected()) return [];

        $conditions = [];
        $params = [];

        if (!empty($filters['keyword'])) {
            $conditions[] = "(lower(domain_name) LIKE :kw OR lower(keyword) LIKE :kw2 OR lower(city) LIKE :kw3 OR lower(state) LIKE :kw4 OR lower(country) LIKE :kw5)";
            $kw = '%' . strtolower($filters['keyword']) . '%';
            $params['kw'] = $kw; $params['kw2'] = $kw; $params['kw3'] = $kw; $params['kw4'] = $kw; $params['kw5'] = $kw;
        }

        if (!empty($filters['city'])) { $conditions[] = "lower(city) LIKE :city"; $params['city'] = '%' . strtolower($filters['city']) . '%'; }
        if (!empty($filters['state'])) { $conditions[] = "lower(state) LIKE :state"; $params['state'] = '%' . strtolower($filters['state']) . '%'; }
        if (!empty($filters['country'])) { $conditions[] = "lower(country) LIKE :country"; $params['country'] = '%' . strtolower($filters['country']) . '%'; }

        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $params['limit'] = $maxResults;

        $sql = "SELECT * FROM domain_sales {$whereClause} LIMIT :limit";
        $result = $this->clickhouse->query($sql, $params);
        return $result ? $result->rows() : [];
    }

    public function getStats(): array
    {
        if (!$this->clickhouse->isConnected()) {
            return [
                'total_sales' => 0, 'total_volume' => 0, 'avg_price' => 0,
                'top_sale' => null, 'unique_countries' => 0, 'unique_cities' => 0,
            ];
        }

        $totalSales = $this->clickhouse->query("SELECT count() as total FROM domain_sales");
        $totalVolume = $this->clickhouse->query("SELECT sum(price) as total FROM domain_sales");
        $avgPrice = $this->clickhouse->query("SELECT avg(price) as avg FROM domain_sales");
        $topSale = $this->clickhouse->query("SELECT domain_name, price FROM domain_sales ORDER BY price DESC LIMIT 1");
        $uniqueCountries = $this->clickhouse->query("SELECT uniqExact(country) as total FROM domain_sales");
        $uniqueCities = $this->clickhouse->query("SELECT uniqExact(city) as total FROM domain_sales");

        return [
            'total_sales' => (int) ($totalSales ? $totalSales->fetchOne('total') : 0),
            'total_volume' => (float) ($totalVolume ? $totalVolume->fetchOne('total') : 0),
            'avg_price' => (float) ($avgPrice ? $avgPrice->fetchOne('avg') : 0),
            'top_sale' => ($topSale ? $topSale->rows() : [])[0] ?? null,
            'unique_countries' => (int) ($uniqueCountries ? $uniqueCountries->fetchOne('total') : 0),
            'unique_cities' => (int) ($uniqueCities ? $uniqueCities->fetchOne('total') : 0),
        ];
    }

    public function getRecentSales(int $limit = 5): array
    {
        if (!$this->clickhouse->isConnected()) return [];

        $sql = "SELECT * FROM domain_sales ORDER BY id DESC LIMIT :limit";
        $result = $this->clickhouse->query($sql, ['limit' => $limit]);
        return $result ? $result->rows() : [];
    }
}
