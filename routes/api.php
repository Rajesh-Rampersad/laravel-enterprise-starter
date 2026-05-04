<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/documents/search', [\App\Http\Controllers\Api\V1\DocumentController::class, 'search']);
    Route::post('/documents', [\App\Http\Controllers\Api\V1\DocumentController::class, 'store']);
});
