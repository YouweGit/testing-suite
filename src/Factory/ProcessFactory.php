<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Factory;

use Override;
use Symfony\Component\Process\Process;

class ProcessFactory implements ProcessFactoryInterface
{
    /**
     * Create a new Process instance.
     *
     *
     */
    #[Override]
    public function create(string $commandLine): Process
    {
        // See https://github.com/composer/composer/blob/1.10.17/src/Composer/Util/ProcessExecutor.php#L68:L72
        return method_exists(Process::class, 'fromShellCommandline')
            ? Process::fromShellCommandline($commandLine) // Symfony >= 4.2
            : new Process($commandLine); // Symfony < 4.2
    }
}
