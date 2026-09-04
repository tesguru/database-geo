<?php

namespace App\Models;

use App\Services\ClickHouseService;
use Illuminate\Database\Eloquent\Model;

class DomainSale extends Model
{
    protected $connection = 'clickhouse';
    protected $table = 'domain_sales';

    protected $fillable = [
        'domain_name',
        'keyword',
        'price',
        'city',
        'state',
        'country',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Create the ClickHouse tables for domain sales and city populations.
     */
    public static function createTable(ClickHouseService $clickhouse): void
    {
        $sales = "CREATE TABLE IF NOT EXISTS domain_sales (
            id UInt32,
            domain_name String,
            keyword String DEFAULT '',
            price Decimal(18,2),
            city String DEFAULT '',
            state String DEFAULT '',
            country String DEFAULT '',
            created_at DateTime DEFAULT now()
        ) ENGINE = MergeTree()
        ORDER BY (id)
        SETTINGS index_granularity = 8192";
        $clickhouse->execute($sales);

        $pops = "CREATE TABLE IF NOT EXISTS city_population (
            id UInt32,
            city String,
            state String DEFAULT '',
            country String DEFAULT '',
            population UInt64 DEFAULT 0,
            created_at DateTime DEFAULT now()
        ) ENGINE = MergeTree()
        ORDER BY (id)
        SETTINGS index_granularity = 8192";
        $clickhouse->execute($pops);
    }

    /**
     * Get the ClickHouse service instance.
     */
    public static function getClickHouse(): ClickHouseService
    {
        return app(ClickHouseService::class);
    }
}
