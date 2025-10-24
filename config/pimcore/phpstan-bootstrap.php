<?php

declare(strict_types=1);

// Using same bootstrap as Pimcore (Bundles) uses themselves for PHPStan
// phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols

if (file_exists(__DIR__ . '/../../../../../vendor/autoload.php')) {
    define('PIMCORE_PROJECT_ROOT', __DIR__ . '/../../../../..');
} elseif (getenv('PIMCORE_PROJECT_ROOT')) {
    if (file_exists(getenv('PIMCORE_PROJECT_ROOT') . '/vendor/autoload.php')) {
        define('PIMCORE_PROJECT_ROOT', getenv('PIMCORE_PROJECT_ROOT'));
    } else {
        throw new \Exception('Invalid Pimcore project root "' . getenv('PIMCORE_PROJECT_ROOT') . '"');
    }
} else {
    throw new \Exception(
        'Unknown configuration! Pimcore project root not found, please set env variable PIMCORE_PROJECT_ROOT.'
    );
}

include PIMCORE_PROJECT_ROOT . '/vendor/autoload.php';
\Pimcore\Bootstrap::setProjectRoot();
\Pimcore\Bootstrap::bootstrap();

if (!defined('PIMCORE_TEST')) {
    define('PIMCORE_TEST', true);
}
