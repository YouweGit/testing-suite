<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Factory;

use Symfony\Component\Process\Process;

interface ProcessFactoryInterface
{
    /**
     * Create a new Process instance.
     *
     *
     */
    public function create(string $commandLine): Process;
}
