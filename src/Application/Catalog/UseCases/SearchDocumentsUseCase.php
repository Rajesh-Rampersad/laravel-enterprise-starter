<?php

declare(strict_types=1);

namespace Enterprise\Application\Catalog\UseCases;

use Enterprise\Domain\Catalog\Repositories\DocumentSearchRepositoryInterface;

final class SearchDocumentsUseCase
{
    public function __construct(
        private readonly DocumentSearchRepositoryInterface $searchRepository
    ) {}

    /**
     * @return array
     */
    public function execute(string $query, string $tenantId): array
    {
        if (empty(trim($query))) {
            return [];
        }

        $documents = $this->searchRepository->search($query, $tenantId);

        return array_map(fn($doc) => $doc->toArray(), $documents);
    }
}
