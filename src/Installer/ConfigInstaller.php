<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Installer;

use Override;
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Youwe\TestingSuite\Composer\ConfigResolver;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ConfigInstaller implements InstallerInterface
{
    private readonly JsonFile $file;

    /**
     * Constructor.
     *
     * @param JsonFile|null  $file
     */
    public function __construct(
        private readonly ConfigResolver $resolver,
        ?JsonFile $file = null
    ) {
        $this->file     = $file ?? new JsonFile(Factory::getComposerFile());
    }

    /**
     * Install.
     */
    #[Override]
    public function install(): void
    {
        $definition = $this->file->read();
        $config     = $definition['config'] ?? [];

        $config = array_replace_recursive(
            $this->resolver->resolve(),
            $config
        );

        $definition['config'] = $config;
        $this->file->write($definition);
    }
}
