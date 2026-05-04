<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::updateOrCreate(
            ['domain' => 'google.com'],
            [
                'id' => '00000000-0000-0000-0000-000000000001',
                'name' => 'Google Inc.',
                'database_name' => 'tenant_google',
            ]
        );

        Tenant::updateOrCreate(
            ['domain' => 'apple.com'],
            [
                'id' => '00000000-0000-0000-0000-000000000002',
                'name' => 'Apple Inc.',
                'database_name' => 'tenant_apple',
            ]
        );
    }
}
