<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Installer;

use Composer\IO\IOInterface;
use Youwe\Composer\FileInstaller as ComposerFileInstaller;
use Youwe\TestingSuite\Composer\MappingResolver;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class FilesInstaller implements InstallerInterface
{
    /** @var MappingResolver */
    private $mappingResolver;

    /** @var ComposerFileInstaller */
    private $fileInstaller;

    /** @var IOInterface */
    private $io;

    /**
     * Constructor.
     *
     * @param MappingResolver       $mappingResolver
     * @param ComposerFileInstaller $fileInstaller
     * @param IOInterface           $io
     */
    public function __construct(
        MappingResolver $mappingResolver,
        ComposerFileInstaller $fileInstaller,
        IOInterface $io
    ) {
        $this->mappingResolver = $mappingResolver;
        $this->fileInstaller   = $fileInstaller;
        $this->io              = $io;
    }

    /**
     * Install.
     *
     * @return void
     */
    public function install()
    {
        foreach ($this->mappingResolver->resolve() as $mapping) {
            if (file_exists($mapping->getDestination())) {
                continue;
            }

            $this->fileInstaller->installFile($mapping);

            $this->io->write(
                sprintf(
                    '<info>Installed:</info> %s',
                    $mapping->getRelativeDestination()
                )
            );
        }
    }
}
