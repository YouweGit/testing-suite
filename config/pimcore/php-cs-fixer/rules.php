<?php

declare(strict_types=1);

return [
    // Alias for the latest revision of PER-CS rules
    '@PER-CS' => true,

    // Each file should have a `declare(strict_types=x)`. When it doesn't it will be added with `strict_types=1`. Set it
    // to `strict_types=0` explicitly if your file shouldn't use strict type checking.
    'declare_strict_types' => ['strategy' => 'add_when_missing'],
];
