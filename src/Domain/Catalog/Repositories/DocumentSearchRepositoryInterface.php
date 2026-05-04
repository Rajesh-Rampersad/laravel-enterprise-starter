<?php

declare(strict_types=1);

namespace Enterprise\Domain\Catalog\Repositories;

use Enterprise\Domain\Catalog\Entities\Document;

interface DocumentSearchRepositoryInterface
{
    public function index(Document $document, string $tenantId): void;
    
    public function remove(string $id, string $tenantId): void;
    
    /**
     * @return Document[]
     */
    public function search(string $query, string $tenantId): array;
}
