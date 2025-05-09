<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Installer;

interface InstallerInterface
{
    /**
     * Install.
     */
    public function install(): void;
}
