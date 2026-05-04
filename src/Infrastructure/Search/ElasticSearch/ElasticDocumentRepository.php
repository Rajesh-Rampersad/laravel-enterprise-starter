<?php

declare(strict_types=1);

namespace Enterprise\Infrastructure\Search\ElasticSearch;

use Elastic\Elasticsearch\Client;
use Enterprise\Domain\Catalog\Entities\Document;
use Enterprise\Domain\Catalog\Repositories\DocumentSearchRepositoryInterface;
use DateTimeImmutable;

final class ElasticDocumentRepository implements DocumentSearchRepositoryInterface
{
    private const INDEX_PREFIX = 'documents_';

    public function __construct(
        private readonly Client $client
    ) {}

    public function index(Document $document, string $tenantId): void
    {
        $this->client->index([
            'index' => $this->getIndexName($tenantId),
            'id' => $document->getId(),
            'body' => $document->toArray(),
        ]);
    }

    public function remove(string $id, string $tenantId): void
    {
        $this->client->delete([
            'index' => $this->getIndexName($tenantId),
            'id' => $id,
        ]);
    }

    public function search(string $query, string $tenantId): array
    {
        $response = $this->client->search([
            'index' => $this->getIndexName($tenantId),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['title', 'content'],
                    ],
                ],
            ],
        ]);

        $hits = $response['hits']['hits'] ?? [];
        
        return array_map(function (array $hit) {
            $source = $hit['_source'];
            return new Document(
                id: $source['id'],
                title: $source['title'],
                content: $source['content'],
                createdAt: new DateTimeImmutable($source['created_at']),
                updatedAt: new DateTimeImmutable($source['updated_at'])
            );
        }, $hits);
    }

    private function getIndexName(string $tenantId): string
    {
        return self::INDEX_PREFIX . str_replace('-', '_', $tenantId);
    }
}
