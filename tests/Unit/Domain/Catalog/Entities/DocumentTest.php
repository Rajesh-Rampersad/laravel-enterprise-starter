<?php

declare(strict_types=1);

use Enterprise\Domain\Catalog\Entities\Document;

test('it can create a document entity', function () {
    $id = '550e8400-e29b-41d4-a716-446655440000';
    $title = 'Test Title';
    $content = 'Test Content';
    $createdAt = new DateTimeImmutable();
    $updatedAt = new DateTimeImmutable();

    $document = new Document($id, $title, $content, $createdAt, $updatedAt);

    expect($document->getId())->toBe($id)
        ->and($document->getTitle())->toBe($title)
        ->and($document->getContent())->toBe($content)
        ->and($document->getCreatedAt())->toBe($createdAt)
        ->and($document->getUpdatedAt())->toBe($updatedAt);
});

test('it can convert document to array', function () {
    $id = '550e8400-e29b-41d4-a716-446655440000';
    $title = 'Test Title';
    $content = 'Test Content';
    $createdAt = new DateTimeImmutable('2024-05-03 10:00:00');
    $updatedAt = new DateTimeImmutable('2024-05-03 11:00:00');

    $document = new Document($id, $title, $content, $createdAt, $updatedAt);
    $array = $document->toArray();

    expect($array)->toBe([
        'id' => $id,
        'title' => $title,
        'content' => $content,
        'created_at' => $createdAt->format(DATE_ATOM),
        'updated_at' => $updatedAt->format(DATE_ATOM),
    ]);
});
