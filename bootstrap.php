<?php



declare(strict_types=1);

use App\Models\Stock;
use App\Repositories\ProductRepository;
use Core\Contracts\DatabaseDriver;
use Core\Contracts\ReportableInterface;
use Core\Contracts\RepositoryInterface;
use Core\Database\Database;
use Core\Database\DatabaseFactory;
use Core\Http\Request;
use Core\Http\Router;
use Core\View\View;



require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/load.php';

$config = loadAppConfig();
$container = new Core\Container\Container();

try {
    $driver = DatabaseFactory::make($config);
} catch (Throwable $exception) {
    renderDatabaseError($exception->getMessage());
    exit(1);
}


$container->bind(DatabaseDriver::class, static fn (): DatabaseDriver => $driver);
$container->bind(RepositoryInterface::class, ProductRepository::class);
$container->bind(ReportableInterface::class, Stock::class);

View::setBasePath(__DIR__ . '/app/Views');

try {
    $database = $container->resolve(Database::class);
    require __DIR__ . '/database/migrate.php';
    migrate($database);
} catch (Throwable $exception) {
    renderDatabaseError($exception->getMessage());
    exit(1);
}

$router = new Router();
(require __DIR__ . '/routes.php')($router);

$request = Request::capture();
$response = $router->dispatch($request, $container);
$response->send();

function renderDatabaseError(string $message): void
{
    http_response_code(500);
    View::setBasePath(__DIR__ . '/app/Views');
    echo View::render('layout', [
        'title' => 'Database Error',
        'content' => View::render('errors/database', ['message' => $message]),
    ]);
}
 



