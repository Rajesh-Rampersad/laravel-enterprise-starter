<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Jobs\SyncDocumentToSearchJob;
use App\Models\Document;
use App\Models\Tenant;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

uses(RefreshDatabase::class);

test('it dispatches sync document job to queue on creation', function () {
    // 1. Fake the queue
    Queue::fake();

    $tenantId = '00000000-0000-0000-0000-000000000001';
    
    // 2. Setup database context
    config(['database.connections.tenant.driver' => 'sqlite']);
    config(['database.connections.tenant.database' => ':memory:']);

    // 3. Create a tenant and perform the request
    Tenant::create([
        'id' => $tenantId,
        'name' => 'Test Tenant',
        'domain' => 'test.com',
        'database_name' => 'tenant_test',
    ]);

    $this->postJson('/api/v1/documents', [
        'title' => 'Test Doc',
        'content' => 'Test Content',
    ], [
        'X-Tenant-ID' => $tenantId
    ])->assertStatus(201);

    // 4. Assert the job was dispatched with correct data
    Queue::assertPushed(SyncDocumentToSearchJob::class, function (SyncDocumentToSearchJob $job) use ($tenantId) {
        return $job->getTenantId() === $tenantId && $job->getAction() === 'index';
    });
});

