<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer;

class ConfigResolver
{
    /** @var string */
    private $template = __DIR__ . '/../templates/config/%s.json';

    /**
     * Constructor.
     */
    public function __construct(
        private readonly ProjectTypeResolver $typeResolver,
        ?string $template = null
    ) {
        $this->template     = $template ?? $this->template;
    }

    /**
     * Resolve config.
     */
    public function resolve(): array
    {
        $file = sprintf($this->template, $this->typeResolver->resolve());

        if (!file_exists($file)) {
            $file = sprintf($this->template, 'default');
        }

        return json_decode(file_get_contents($file), true);
    }
}
