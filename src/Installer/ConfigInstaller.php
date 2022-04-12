<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Installer;

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
    /** @var JsonFile */
    private $file;

    /** @var ConfigResolver */
    private $resolver;

    /**
     * Constructor.
     *
     * @param ConfigResolver $resolver
     * @param JsonFile|null  $file
     */
    public function __construct(
        ConfigResolver $resolver,
        JsonFile $file = null
    ) {
        $this->resolver = $resolver;
        $this->file     = $file ?? new JsonFile(Factory::getComposerFile());
    }

    /**
     * Install.
     *
     * @return void
     */
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
