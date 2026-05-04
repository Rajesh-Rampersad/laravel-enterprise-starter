<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Tenant;
use Enterprise\Infrastructure\Persistence\TenantConnectionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

final class TenantsMigrateCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'tenants:migrate {--fresh : Whether to run fresh migrations} {--seed : Whether to seed the database}';

    /**
     * @var string
     */
    protected $description = 'Run migrations for all tenants';

    public function __construct(
        private readonly TenantConnectionService $connectionService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            $this->info('No tenants found.');
            return 0;
        }

        foreach ($tenants as $tenant) {
            $this->migrate($tenant);
        }

        $this->info('Migrations completed for all tenants.');

        return 0;
    }

    private function migrate(Tenant $tenant): void
    {
        $this->info("Migrating tenant: {$tenant->name} ({$tenant->database_name})");

        // Connect to the tenant
        $this->connectionService->connect($tenant);

        $command = $this->option('fresh') ? 'migrate:fresh' : 'migrate';

        Artisan::call($command, [
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);

        if ($this->option('seed')) {
            Artisan::call('db:seed', [
                '--force' => true,
            ]);
        }

        $this->info(Artisan::output());

        // Disconnect to reset state
        $this->connectionService->disconnect();
    }
}
