<?php

declare(strict_types=1);

namespace Enterprise\Application\Catalog\DTOs;

final class CreateDocumentDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $content
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? '',
            content: $data['content'] ?? ''
        );
    }
}
