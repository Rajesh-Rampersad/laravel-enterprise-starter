<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Enterprise\Application\Catalog\DTOs\CreateDocumentDTO;
use Enterprise\Application\Catalog\UseCases\CreateDocumentUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class DocumentController extends Controller
{
    public function __construct(
        private readonly CreateDocumentUseCase $createDocumentUseCase,
        private readonly \Enterprise\Application\Catalog\UseCases\SearchDocumentsUseCase $searchDocumentsUseCase
    ) {}

    public function search(Request $request): JsonResponse
    {
        $query = $request->query('q', '');
        $tenantId = $request->header('X-Tenant-ID');

        $results = $this->searchDocumentsUseCase->execute($query, (string) $tenantId);

        return response()->json($results);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $dto = CreateDocumentDTO::fromArray($request->only(['title', 'content']));
        
        $document = $this->createDocumentUseCase->execute($dto);

        return response()->json($document->toArray(), 201);
    }
}
