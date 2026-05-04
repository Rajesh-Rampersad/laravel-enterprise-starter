<?php

declare(strict_types=1);

namespace App\Providers;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class SearchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Client::class, function () {
            return ClientBuilder::create()
                ->setHosts(['http://elasticsearch:9200'])
                ->build();
        });

        $this->app->bind(
            \Enterprise\Domain\Catalog\Repositories\DocumentSearchRepositoryInterface::class,
            \Enterprise\Infrastructure\Search\ElasticSearch\ElasticDocumentRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
