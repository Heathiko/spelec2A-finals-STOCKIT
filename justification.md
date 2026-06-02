# SOLID Design Principles — Justification Document
**Author:** Maria Katrina O. Esclamado  
**Project:** Stockit Inventory — Custom PHP MVC Framework  
**Course:** Advanced Web Development — Final Project

---

## Overview

This document explains how each of the five SOLID design principles is applied throughout the Stockit Inventory framework. Each principle is demonstrated with references to specific classes, methods, and design decisions in the codebase.

---

## S — Single Responsibility Principle
*"A class should have only one reason to change."*

Every class in the framework is designed with a single, well-defined job:

- **`Router.php`** — responsible only for matching incoming HTTP requests to route handlers. It does not build controllers, validate input, or send responses.
- **`Validator.php`** — responsible only for checking input data against a set of rules. It has no knowledge of the database, HTTP, or views.
- **`Database.php`** — responsible only for executing prepared SQL statements via PDO. It does not build queries or map results to objects.
- **`View.php`** — responsible only for locating and rendering PHP template files. It does not handle routing or data fetching.
- **`Request.php`** — responsible only for capturing and representing an incoming HTTP request. It does not dispatch or validate.
- **`Response.php`** — responsible only for representing an HTTP response. It does not render templates or interact with the database.

Each class has exactly one reason to change. If the routing logic needs to change, only `Router.php` is affected. If the validation rules engine changes, only `Validator.php` is affected.

---

## O — Open/Closed Principle
*"Software entities should be open for extension but closed for modification."*

The base `Model` class in `core/Database/Model.php` demonstrates this principle most clearly.

`Model` is an abstract class that provides reusable CRUD methods — `all()`, `find()`, `insert()`, `update()`, and `remove()` — built dynamically around an abstract `table()` method:

```php
abstract protected function table(): string;
```

Child models only need to declare their table name:

```php
// app/Models/Product.php
final class Product extends Model {
    protected function table(): string {
        return 'products';
    }
}

// app/Models/Category.php
final class Category extends Model {
    protected function table(): string {
        return 'categories';
    }
}
```

Adding a new model — such as `Supplier` or `Movie` — requires zero changes to `Model.php`. The core is closed for modification but open for extension through inheritance. This pattern means the entire CRUD layer is reusable across any number of domain models without touching framework code.

The `DatabaseDriver` interface further demonstrates Open/Closed — adding a new database engine (e.g. `SQLiteDriver`) requires implementing the interface without touching `Database.php` or any other existing class.

---

## L — Liskov Substitution Principle
*"Subtypes must be fully substitutable for their base types."*

All application models — `Product`, `Category`, and `Stock` — can substitute for their parent types wherever those types are expected, without breaking the application.

`Product` and `Category` extend `Core\Database\Model`. Any code that depends on `Model` can receive a `Product` or `Category` instance and call `all()`, `find()`, `insert()`, `update()`, or `remove()` with identical behavior and no surprises.

Similarly, `MySQLDriver` implements `DatabaseDriver`:

```php
interface DatabaseDriver {
    public function connect(): PDO;
}
```

`MySQLDriver` and `SQLiteDriver` both implement this interface with the same method signature and the same contract — return a PDO connection. Either driver can be substituted wherever `DatabaseDriver` is expected without changing any consuming code. `Database.php` receives a `DatabaseDriver` and calls `connect()` — it does not care which driver it receives.

---

## I — Interface Segregation Principle
*"No client should be forced to depend on methods it does not use."*

Rather than defining one large repository interface with all CRUD methods, the contracts are split into two focused interfaces:

**`Findable.php`:**
```php
interface Findable {
    public function all(): array;
    public function find(int $id): ?array;
}
```

**`Persistable.php`:**
```php
interface Persistable {
    public function create(array $data): int;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
```

`RepositoryInterface` combines both for full CRUD repositories. However, `Stock` — which only generates reports and never performs write operations — implements only `ReportableInterface`:

```php
interface ReportableInterface {
    public function generateReport(): array;
}
```

If a single fat interface were used, `Stock` would be forced to stub out `create()`, `update()`, and `delete()` methods it will never use. By splitting the interfaces, `Stock` depends only on the contract it actually fulfills. A read-only repository would implement only `Findable` without being forced to stub write methods.

---

## D — Dependency Inversion Principle
*"High-level modules should not depend on low-level modules. Both should depend on abstractions."*

The Dependency Inversion Principle is most visible in two places:

### 1. StockController
`StockController` declares its dependency on `ReportableInterface`, not on the concrete `Stock` class:

```php
final class StockController extends Controller {
    public function __construct(
        private readonly ReportableInterface $stockReport
    ) {}
}
```

`StockController` is a high-level module — it orchestrates the stock report feature. It never references `Stock` directly. In `bootstrap.php`, the container binds the interface to the concrete class:

```php
$container->bind(ReportableInterface::class, Stock::class);
```

If the report source changes — for example, switching to a cached report or an external API — only the bootstrap binding changes. `StockController` requires no modification.

### 2. Container and Repositories
Controllers depend on repository interfaces injected by the container, not on concrete model classes. `ProductController` receives `ProductRepository` through constructor injection — it never calls `new ProductRepository()`. The container resolves the full dependency tree automatically using `ReflectionClass` to read constructor type hints at runtime:

```php
// Container reads this constructor:
public function __construct(
    private readonly ProductRepository $products,
    private readonly CategoryRepository $categories,
    private readonly Validator $validator,
)
// and builds each dependency recursively without any manual wiring
```

This means high-level modules (controllers) are completely decoupled from low-level infrastructure (database drivers, concrete repositories). Swapping implementations requires only a single binding change in `bootstrap.php`.

---

## Summary

| Principle | Key Class | How Applied |
|-----------|-----------|-------------|
| **S** — Single Responsibility | `Router`, `Validator`, `Database`, `View` | Each class has exactly one job and one reason to change |
| **O** — Open/Closed | `Model`, `DatabaseDriver` | Extended via inheritance and interfaces without modifying core |
| **L** — Liskov Substitution | `Product`, `Category`, `MySQLDriver` | All subtypes fully substitutable for their base types |
| **I** — Interface Segregation | `Findable`, `Persistable`, `ReportableInterface` | Interfaces split so classes only implement what they use |
| **D** — Dependency Inversion | `StockController`, `Container` | High-level modules depend on abstractions injected at runtime |
