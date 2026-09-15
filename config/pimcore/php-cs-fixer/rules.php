<?php

declare(strict_types=1);

return [
    // Alias for the latest revision of PER-CS rules
    '@PER-CS' => true,

    // Each file should have a `declare(strict_types=x)`. When it doesn't it will be added with `strict_types=1`. Set it
    // to `strict_types=0` explicitly if your file shouldn't use strict type checking.
    'declare_strict_types' => ['strategy' => 'add_when_missing'],

    'no_extra_blank_lines' => [
        'tokens' => [
            'attribute',
            // 'break', // Do allow after `break;` which can help to make large switch statements more readable
            'case',
            // 'comma', // Do allow after a comma, which can help to make large array or match structures more readable
            'continue',
            'curly_brace_block',
            'default',
            'extra',
            'parenthesis_brace_block',
            'return',
            'square_brace_block',
            'switch',
            'throw',
            'use',
            'use_trait',
        ],
    ],
];
