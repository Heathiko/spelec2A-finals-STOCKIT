<?php

declare(strict_types=1);

use App\Controllers\CategoryController;
use App\Controllers\ProductController;
use App\Controllers\StockController;
use Core\Http\Router;

return static function (Router $router): void {
    $router->get('/products', [ProductController::class, 'index']);
    $router->get('/products/create', [ProductController::class, 'create']);
    $router->get('/products/{id}', [ProductController::class, 'show']);
    $router->get('/products/{id}/edit', [ProductController::class, 'edit']);
    $router->post('/products', [ProductController::class, 'store']);
    $router->post('/products/{id}/edit', [ProductController::class, 'update']);
    $router->post('/products/{id}/delete', [ProductController::class, 'destroy']);

    $router->get('/categories', [CategoryController::class, 'index']);
    $router->get('/categories/create', [CategoryController::class, 'create']);
    $router->post('/categories', [CategoryController::class, 'store']);
    $router->get('/stock', [StockController::class, 'index']);

    $router->get('/', [ProductController::class, 'index']);
};
