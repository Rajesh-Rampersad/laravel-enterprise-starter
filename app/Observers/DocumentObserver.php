<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Document as EloquentDocument;
use App\Jobs\SyncDocumentToSearchJob;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;


final class DocumentObserver
{
    public function created(EloquentDocument $eloquentDocument): void
    {
        $this->dispatchSyncJob($eloquentDocument, 'index');
    }

    public function updated(EloquentDocument $eloquentDocument): void
    {
        $this->dispatchSyncJob($eloquentDocument, 'index');
    }

    public function deleted(EloquentDocument $eloquentDocument): void
    {
        $this->dispatchSyncJob($eloquentDocument, 'remove');
    }

    private function dispatchSyncJob(EloquentDocument $eloquentDocument, string $action): void
    {
        $tenantId = Request::header('X-Tenant-ID');
        
        if (!$tenantId) {
            Log::warning("Skipping ES sync job dispatch: No X-Tenant-ID header found.");
            return;
        }

        SyncDocumentToSearchJob::dispatch(
            [
                'id' => $eloquentDocument->id,
                'title' => $eloquentDocument->title,
                'content' => $eloquentDocument->content,
                'created_at' => $eloquentDocument->created_at->toDateTimeString(),
                'updated_at' => $eloquentDocument->updated_at->toDateTimeString(),
            ],
            (string) $tenantId,
            $action
        );
    }
}

