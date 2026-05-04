<?php

declare(strict_types=1);

namespace Enterprise\Domain\Catalog\Repositories;

use Enterprise\Domain\Catalog\Entities\Document;

interface DocumentRepositoryInterface
{
    public function findById(string $id): ?Document;
    
    public function save(Document $document): void;
    
    public function delete(string $id): void;
    
    /**
     * @return Document[]
     */
    public function findAll(): array;
}
