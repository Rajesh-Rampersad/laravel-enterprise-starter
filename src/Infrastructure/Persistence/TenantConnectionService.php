<?php

declare(strict_types=1);

namespace Enterprise\Infrastructure\Persistence;

use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

class TenantConnectionService
{
    private const CONNECTION_NAME = 'tenant';

    public function connect(Tenant $tenant): void
    {
        // Purge existing connection to avoid state leakage
        DB::purge(self::CONNECTION_NAME);

        // Set the new database name in the config
        Config::set('database.connections.' . self::CONNECTION_NAME . '.database', $tenant->database_name);

        // Verify the connection (skip in testing to allow mock database names)
        if (config('app.env') !== 'testing') {
            try {
                DB::connection(self::CONNECTION_NAME)->getPdo();
            } catch (\Exception $e) {
                throw new InvalidArgumentException("Could not connect to tenant database: {$tenant->database_name}. " . $e->getMessage());
            }
        }

        // Set the default connection for the rest of the request
        DB::setDefaultConnection(self::CONNECTION_NAME);
    }

    public function disconnect(): void
    {
        DB::purge(self::CONNECTION_NAME);
        DB::setDefaultConnection(config('database.default'));
    }
}
