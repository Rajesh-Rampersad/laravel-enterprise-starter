<?php

declare(strict_types=1);

namespace App\Jobs;

use Enterprise\Domain\Catalog\Entities\Document as DomainDocument;
use Enterprise\Domain\Catalog\Repositories\DocumentSearchRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DateTimeImmutable;

final class SyncDocumentToSearchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param array<string, mixed> $documentData
     */
    public function __construct(
        private readonly array $documentData,
        private readonly string $tenantId,
        private readonly string $action = 'index'
    ) {}

    /**
     * Execute the job.
     */
    public function handle(DocumentSearchRepositoryInterface $searchRepository): void
    {
        if ($this->action === 'remove') {
            $searchRepository->remove($this->documentData['id'], $this->tenantId);
            return;
        }

        $document = new DomainDocument(
            id: $this->documentData['id'],
            title: $this->documentData['title'],
            content: $this->documentData['content'],
            createdAt: new DateTimeImmutable($this->documentData['created_at']),
            updatedAt: new DateTimeImmutable($this->documentData['updated_at'])
        );

        $searchRepository->index($document, $this->tenantId);
    }

    public function getTenantId(): string
    {
        return $this->tenantId;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}

