<?php

declare(strict_types=1);

function loadAppConfig(): array
{
    $config = require __DIR__ . '/app.php';
    $localPath = __DIR__ . '/app.local.php';

    if (!is_file($localPath)) {
        return $config;
    }

    $local = require $localPath;
    return array_replace_recursive($config, $local);
}
