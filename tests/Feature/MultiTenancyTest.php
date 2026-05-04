<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Tenant;
use Enterprise\Infrastructure\Persistence\TenantConnectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

uses(RefreshDatabase::class);

test('it identifies tenant from header and switches connection', function () {
    // 1. Get the global mock instance
    $mockService = $this->app->make(TenantConnectionService::class);

    // 2. Setup: Create a tenant in the central DB
    $tenant = Tenant::create([
        'id' => '00000000-0000-0000-0000-000000000001',
        'name' => 'Test Tenant',
        'domain' => 'test.com',
        'database_name' => 'tenant_test',
    ]);

    // 3. Expect connect() to be called with this tenant
    $mockService->shouldReceive('connect')
        ->once()
        ->with(Mockery::on(fn($t) => $t->id === $tenant->id));

    // 4. Hit the endpoint
    $this->mock(\Enterprise\Domain\Catalog\Repositories\DocumentSearchRepositoryInterface::class)
        ->shouldReceive('search')
        ->andReturn([]);

    $this->getJson('/api/v1/documents/search?q=test', [
        'X-Tenant-ID' => $tenant->id
    ])->assertStatus(200);
});


test('it returns 400 if header is missing', function () {
    $this->getJson('/api/v1/documents/search?q=test')
        ->assertStatus(400)
        ->assertJson(['error' => 'X-Tenant-ID header is missing.']);
});

test('it returns 404 if tenant is not found', function () {
    $this->getJson('/api/v1/documents/search?q=test', [
        'X-Tenant-ID' => '00000000-0000-0000-0000-000000000000'
    ])->assertStatus(404);
});
