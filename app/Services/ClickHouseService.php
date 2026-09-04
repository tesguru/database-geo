<?php

namespace App\Services;

use ClickHouseDB\Client;

class ClickHouseService
{
    protected ?Client $client = null;
    protected bool $connected = false;

    public function __construct()
    {
        try {
            $this->client = new Client([
                'host' => config('database.connections.clickhouse.host', '127.0.0.1'),
                'port' => config('database.connections.clickhouse.port', 8123),
                'username' => config('database.connections.clickhouse.username', 'default'),
                'password' => config('database.connections.clickhouse.password', ''),
            ]);

            $this->client->database(config('database.connections.clickhouse.database', 'databasegeo'));

            $this->client->select('SELECT 1');
            $this->connected = true;
        } catch (\Exception $e) {
            $this->connected = false;
            $this->client = null;
        }
    }

    public function isConnected(): bool
    {
        return $this->connected;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function query(string $sql, array $params = []): ?\ClickHouseDB\Statement
    {
        if (!$this->connected || !$this->client) return null;

        try {
            $stmt = $this->client->select($sql, $params);

            try { $stmt->countAll(); } catch (\Exception $ignored) {}

            return $stmt;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function execute(string $sql): bool
    {
        if (!$this->connected || !$this->client) return false;

        try {
            $this->client->write($sql);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function insert(string $table, array $rows, array $columns = []): bool
    {
        if (!$this->connected || !$this->client) return false;

        try {
            $this->client->insert($table, $rows, $columns);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
