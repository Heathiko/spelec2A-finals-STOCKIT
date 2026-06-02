<?php


//CREATES THE DB IF NEEDEDDDD

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php'; //autoloaded when i installed composer---it loads up my composer.json dependencies if i understand it correctly lol
require dirname(__DIR__) . '/config/load.php';



$config = loadAppConfig();

echo "Stockit database setup. \n";
echo "Driver: {$config['database']['driver']}\n\n";

try{
    $driver = Core\Database\DatabaseFactory::make($config);
    $database = new Core\Database\Database($driver);
    require __DIR__ . '/migrate.php';
    migrate($database);

    $products = (int) $database->fetchOne('SELECT COUNT(*) AS total FROM products')['total'];
    $categories= (int) $database->fetchOne('SELECT COUNT(*) AS total FROM categories')['total'];




    echo "Setup Complete. \n";
    echo"Categories:{$categories}\n";
    echo"Products:{products}\n";
    echo"Successsssssssss\n\n\n\n\n\n";
} catch (Throwable $exception){

    fwrite(STDERR, "Setup Failed: {$exception->getMessage()}\n");
    fwrite(STDERR, "Tip: copy config/app.local.php\n");

}
