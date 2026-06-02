<?php

declare(strict_types=1);

namespace Core\Database;
// decides which driver to instantiate based on configz
use Core\Contracts\DatabaseDriver;
use RuntimeException;

final class DatabaseFactory
{
    public static function make(array $config): DatabaseDriver //DECIde which db to use sql or sqlite
    {
        $preferredDB = (string) ($config['database']['driver'] ?? 'mysql');

        return match ($preferredDB) {
            'sqlite' => self::sqlite($config),
            'mysql' => self::mysql($config),
            default => self::auto($config),
        };
    }

    public static function auto(array $config): DatabaseDriver //check if not specified 
    {
        if (extension_loaded('pdo_mysql')) {
            return self::mysql($config);
        }

        if (extension_loaded('pdo_sqlite')) {
            return self::sqlite($config);
        }

        throw new RuntimeException(
            'No database driver available. Enable pdo_mysql or pdo_sqlite in php.ini.',
        );
    }

    private static function mysql(array $config): DatabaseDriver //use this if sql is used, return to config onsay need
    {
        if (!extension_loaded('pdo_mysql')) {
            throw new RuntimeException(
                'pdo_mysql is not enabled. Start MySQL and enable extension=pdo_mysql in php.ini, or set database.driver to sqlite.',
            );
        }

        $mysql = $config['database']['mysql'];

        return new MySQLDriver(
            host: (string) $mysql['host'],
            dbname: (string) $mysql['dbname'],
            username: (string) $mysql['username'],
            password: (string) $mysql['password'],
            charset: (string) ($mysql['charset'] ?? 'utf8mb4'),
            createDatabase: (bool) ($mysql['create_database'] ?? true),
        );
    }

    private static function sqlite(array $config): DatabaseDriver //kani kung sqlite
    {
        if (!extension_loaded('pdo_sqlite')) {
            throw new RuntimeException(
                'pdo_sqlite is not enabled. Enable extension=pdo_sqlite in php.ini, or set database.driver to mysql.',
            );
        }

        return new SQLiteDriver(path: (string) $config['database']['sqlite']['path']);
    }
}
