<?php

declare(strict_types=1);

namespace Enterprise\Domain\Catalog\Entities;

use DateTimeImmutable;
use InvalidArgumentException;

class Document
{
    public function __construct(
        private readonly string $id,
        private string $title,
        private string $content,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ) {
        $this->ensureTitleIsNotEmpty($title);
    }

    public static function create(string $id, string $title, string $content): self
    {
        $now = new DateTimeImmutable();
        return new self($id, $title, $content, $now, $now);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function update(string $title, string $content): void
    {
        $this->ensureTitleIsNotEmpty($title);
        $this->title = $title;
        $this->content = $content;
        $this->updatedAt = new DateTimeImmutable();
    }

    private function ensureTitleIsNotEmpty(string $title): void
    {
        if (empty(trim($title))) {
            throw new InvalidArgumentException('Document title cannot be empty.');
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->createdAt->format(DATE_ATOM),
            'updated_at' => $this->updatedAt->format(DATE_ATOM),
        ];
    }
}
