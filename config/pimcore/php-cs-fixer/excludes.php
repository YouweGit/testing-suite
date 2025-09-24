<?php

declare(strict_types=1);

return [
    // Symfony files
    'bin/console',
    'public/index.php',
    'public/bundles',
    // Full var folder, contains all kind of auto-generated files (e.g. cache, generated Pimcore DataObject classes)
    'var',
    // Configuration written by Pimcore (which is written either to var/ or to config/pimcore/ depending on settings)
    'config/pimcore',
    // Unit tests
    'tests/fixtures',
];
