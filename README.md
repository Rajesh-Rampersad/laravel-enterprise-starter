# Laravel Enterprise Starter Boilerplate

[![Pest Tests](https://img.shields.io/badge/tests-100%25%20green-brightgreen)](https://pestphp.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A high-performance, strictly typed Laravel 11 boilerplate for Enterprise SaaS applications. Featuring Database-per-Tenant isolation, Clean Architecture, and asynchronous ElasticSearch synchronization.

## 🚀 Architecture Overview

The following diagram illustrates the request flow and tenant isolation mechanism:

```mermaid
graph TD
    User([User Request]) --> Header{X-Tenant-ID Header}
    Header -- Found --> Middleware[IdentifyTenant Middleware]
    Header -- Missing --> Error400[400 Bad Request]
    
    Middleware --> SwitchConn[TenantConnectionService]
    SwitchConn --> CentralDB[(Central Database)]
    CentralDB -- Find Tenant --> TenantConfig[Tenant Config]
    TenantConfig --> ConnectTenant[(Tenant Database)]
    
    ConnectTenant --> Controller[DocumentController]
    Controller --> UseCase[CreateDocumentUseCase]
    UseCase --> Eloquent[Eloquent Document::create]
    
    Eloquent --> Observer[DocumentObserver]
    Observer --> Job[SyncDocumentToSearchJob]
    Job -- Queue/Redis --> ElasticSearch[[ElasticSearch]]
    
    UseCase --> Response([JSON Response])
```

## 🛠️ Key Features

- **Multi-Tenancy**: Strict database-per-tenant isolation using a custom middleware and connection service.
- **Clean Architecture**: Decoupled Domain, Application, and Infrastructure layers.
- **Asynchronous Search**: ElasticSearch synchronization handled via background jobs (Redis/Horizon) to minimize request latency.
- **Modern Testing**: 100% test coverage using Pest PHP with isolated tenant database support.
- **Strict Typing**: Full PHP 8.3+ strict types and Pydantic-like DTOs.

## 📦 Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
# Configure your DB and ElasticSearch in .env
php artisan migrate --path=database/migrations/central
```

## 🧪 Testing

The project uses **Pest PHP** for testing.

```bash
./vendor/bin/pest

```

# ✅ 100% of Pest tests pass (8 tests, 15 assertions)
![Pest test suite – 100 % passed](docs/pest100.png)




## 📄 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
