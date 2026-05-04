<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \Enterprise\Domain\Catalog\Repositories\DocumentRepositoryInterface::class,
            \Enterprise\Infrastructure\Persistence\Eloquent\EloquentDocumentRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
