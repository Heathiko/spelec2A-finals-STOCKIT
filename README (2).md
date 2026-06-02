# Stockit Inventory
**Author:** Maria Katrina O. Esclamado  
**Course:** Advanced Web Development — Final Project  
**Framework:** Custom PHP MVC (PHP 8.3+)

---

## Overview

Stockit is a custom-built PHP MVC inventory management system built from scratch without any third-party frameworks. It demonstrates a full framework implementation including routing, dependency injection, validation, and database abstraction — all following SOLID design principles and PSR-4 autoloading standards.

---

## Requirements

- PHP 8.3 or higher
- MySQL 5.7+ or MySQL 8.0+
- Composer
- MySQL Workbench or any MySQL client

---

## Setup Instructions

### 1. Clone or download the project

```bash
cd your/project/folder
```

### 2. Install dependencies

```bash
composer install
```

This generates the PSR-4 autoloader in `vendor/`. No external packages are downloaded since the framework is built from scratch.

### 3. Configure the database

Copy the example config and set your credentials:

```
config/app.local.php
```

```php
return [
    'name' => 'Stockit Inventory',
    'debug' => true,
    'database' => [
        'driver' => 'mysql',
        'mysql' => [
            'host'            => 'localhost',
            'dbname'          => 'inventory_db',
            'username'        => 'root',
            'password'        => 'your_password',
            'charset'         => 'utf8mb4',
            'create_database' => true,
        ],
    ],
];
```

### 4. Start the server

```bash
php -S stockit:80 -t public
```

> Add `127.0.0.1 stockit` to your hosts file (`C:\Windows\System32\drivers\etc\hosts`) for the custom domain to work.

### 5. Visit the app

```
http://stockit/products
```

The database and tables are created automatically on first load via `migrate.php`.

---

## Project Structure

```
/
├── app/                        # Application layer (App\ namespace)
│   ├── Controllers/
│   │   ├── ProductController.php
│   │   ├── CategoryController.php
│   │   └── StockController.php
│   ├── Models/
│   │   ├── Product.php
│   │   ├── Category.php
│   │   └── Stock.php
│   ├── Repositories/
│   │   ├── ProductRepository.php
│   │   └── CategoryRepository.php
│   └── Views/
│       ├── products/
│       ├── categories/
│       ├── stock/
│       ├── errors/
│       └── layout.php
├── core/                       # Framework layer (Core\ namespace)
│   ├── Contracts/
│   │   ├── DatabaseDriver.php
│   │   ├── Findable.php
│   │   ├── Persistable.php
│   │   ├── RepositoryInterface.php
│   │   └── ReportableInterface.php
│   ├── Container/
│   │   └── Container.php       # DI container (satisfies SOLID-D)
│   ├── Database/
│   │   ├── Database.php
│   │   ├── Model.php
│   │   ├── MySQLDriver.php
│   │   └── DatabaseFactory.php
│   ├── Http/
│   │   ├── Request.php
│   │   ├── Response.php
│   │   ├── Router.php
│   │   └── HttpMethod.php
│   ├── View/
│   │   └── View.php
│   ├── Controller.php
│   └── Validator.php
├── config/
│   ├── app.php
│   └── load.php
├── database/
│   ├── migrate.php
│   └── setup.php
├── public/
│   └── index.php               # Front controller (only entry point)
├── routes.php                  # All route definitions
├── bootstrap.php               # App bootstrapper
└── composer.json               # PSR-4 namespaces
```

---

## All Routes

| Method | URI | Controller | Action | Description |
|--------|-----|------------|--------|-------------|
| GET | `/` | ProductController | index | Redirect to products |
| GET | `/products` | ProductController | index | List all products |
| GET | `/products/create` | ProductController | create | Show create form |
| GET | `/products/{id}` | ProductController | show | View single product |
| GET | `/products/{id}/edit` | ProductController | edit | Show edit form |
| POST | `/products` | ProductController | store | Save new product |
| POST | `/products/{id}/edit` | ProductController | update | Update product |
| POST | `/products/{id}/delete` | ProductController | destroy | Delete product |
| GET | `/categories` | CategoryController | index | List all categories |
| GET | `/categories/create` | CategoryController | create | Show create form |
| POST | `/categories` | CategoryController | store | Save new category |
| GET | `/stock` | StockController | index | Stock level report |

---

## Framework Design Decisions

### PSR-4 Autoloading
Two namespaces are defined in `composer.json`:
- `Core\` → maps to `core/` — the reusable framework layer
- `App\` → maps to `app/` — the application layer

No manual `require` or `include` is used anywhere except the entry point `public/index.php`.

### Front Controller Pattern
All requests go through `public/index.php`, which requires `bootstrap.php`. This single entry point ensures consistent request handling, middleware application, and error management.

### MVC Separation
- **Models** — handle data and database interaction only
- **Views** — plain PHP templates, no business logic
- **Controllers** — handle request/response flow, delegate to repositories

### Dependency Injection Container
`Container.php` uses PHP's `ReflectionClass` to read constructor type hints at runtime and automatically inject dependencies. Controllers never instantiate their own dependencies — they declare what they need and the container provides it.

### Repository Pattern
Controllers depend on repository interfaces, not concrete model classes. This decouples the controller from the database implementation and makes the system testable and swappable.

### Automatic Database Creation
`MySQLDriver` automatically creates the database if it doesn't exist, reducing setup friction for new developers.

---

## MVP Application

Stockit is an inventory management system for tracking products, categories, and stock levels.

### Features
- **Products** — Full CRUD: create, read, update, and delete products with name, SKU, description, price, quantity, and category
- **Categories** — View and create product categories
- **Stock Report** — A live report showing all products with their stock status (Low / Medium / Healthy), inventory value per item, and total units and value across all stock
- **Validation** — Server-side validation on all forms with error messages and field repopulation on failure
- **Auto-migration** — Database tables are created automatically on first run

### Database Schema
- `categories` — id, name, description
- `products` — id, category_id (FK), name, sku, description, quantity, price
