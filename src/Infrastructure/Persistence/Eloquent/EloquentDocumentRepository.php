<?php

declare(strict_types=1);

namespace Enterprise\Infrastructure\Persistence\Eloquent;

use Enterprise\Domain\Catalog\Entities\Document as DomainDocument;
use Enterprise\Domain\Catalog\Repositories\DocumentRepositoryInterface;
use App\Models\Document as EloquentDocument;
use DateTimeImmutable;

final class EloquentDocumentRepository implements DocumentRepositoryInterface
{
    public function findById(string $id): ?DomainDocument
    {
        $eloquent = EloquentDocument::find($id);
        
        if (!$eloquent) {
            return null;
        }

        return $this->toDomain($eloquent);
    }

    public function save(DomainDocument $document): void
    {
        EloquentDocument::updateOrCreate(
            ['id' => $document->getId()],
            [
                'title' => $document->getTitle(),
                'content' => $document->getContent(),
                'created_at' => $document->getCreatedAt(),
                'updated_at' => $document->getUpdatedAt(),
            ]
        );
    }

    public function delete(string $id): void
    {
        EloquentDocument::destroy($id);
    }

    public function findAll(): array
    {
        return EloquentDocument::all()
            ->map(fn(EloquentDocument $doc) => $this->toDomain($doc))
            ->toArray();
    }

    private function toDomain(EloquentDocument $eloquent): DomainDocument
    {
        return new DomainDocument(
            id: $eloquent->id,
            title: $eloquent->title,
            content: $eloquent->content,
            createdAt: new DateTimeImmutable($eloquent->created_at->toDateTimeString()),
            updatedAt: new DateTimeImmutable($eloquent->updated_at->toDateTimeString())
        );
    }
}
