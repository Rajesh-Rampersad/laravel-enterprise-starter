<?php

declare(strict_types=1);

namespace Enterprise\Application\Catalog\UseCases;

use Enterprise\Application\Catalog\DTOs\CreateDocumentDTO;
use Enterprise\Domain\Catalog\Entities\Document;
use Enterprise\Domain\Catalog\Repositories\DocumentRepositoryInterface;
use Illuminate\Support\Str;

final class CreateDocumentUseCase
{
    public function __construct(
        private readonly DocumentRepositoryInterface $repository
    ) {}

    public function execute(CreateDocumentDTO $dto): Document
    {
        $document = Document::create(
            id: (string) Str::uuid(),
            title: $dto->title,
            content: $dto->content
        );

        $this->repository->save($document);

        return $document;
    }
}
