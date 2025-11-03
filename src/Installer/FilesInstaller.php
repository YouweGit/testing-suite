<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Installer;

use Composer\IO\IOInterface;
use Youwe\Composer\FileInstaller as ComposerFileInstaller;
use Youwe\FileMapping\FileMappingInterface;
use Youwe\FileMapping\FileMappingReaderInterface;
use Youwe\TestingSuite\Composer\MappingResolver;

/**
 * Add configuration files on package-change to avoid GrumPHP installation asking to do the same (which will run
 * post-install-cmd)
 *
 * @SuppressWarnings("PHPMD.ShortVariable")
 */
class FilesInstaller implements PostPackageChangeInstallerInterface
{
    /**
     * Constructor.
     *
     * @param MappingResolver       $mappingResolver
     * @param IOInterface           $io
     */
    public function __construct(
        private readonly MappingResolver $mappingResolver,
        private readonly IOInterface $io,
    ) {
    }

    /**
     * Install.
     *
     * @return void
     */
    public function installPostPackageChange(): void
    {
        $fileMappingReader = $this->mappingResolver->resolve();

        // Rewrite old to new paths on existing files
        foreach ($fileMappingReader as $mapping) {
            if (file_exists($mapping->getDestination())) {
                $this->resolveYouwePathing($mapping);
            }
        }

        // Install files via Composer File Installer
        $this->getComposerFileInstaller($fileMappingReader)->install($this->io);
    }

    protected function getComposerFileInstaller(FileMappingReaderInterface $fileMappingReader): ComposerFileInstaller
    {
        // As separate method for testing/mocking purposes
        return new ComposerFileInstaller($fileMappingReader);
    }

    /**
     * @param FileMappingInterface $unixFileMapping
     *
     * @SuppressWarnings("PHPMD.CyclomaticComplexity")
     *
     * @return void
     */
    private function resolveYouwePathing(FileMappingInterface $unixFileMapping): void
    {
        $name = $unixFileMapping->getRelativeDestination();

        if ($this->mappingResolver->getTypeResolver()->resolve() === 'magento2') {
            // Reference updates for Magento 2 projects
            if ($name === "phpcs.xml") {
                $this->updatePath(
                    $unixFileMapping->getDestination(),
                    [
                        './vendor/mediact/coding-standard-magento2/src/MediactMagento2',
                        './vendor/mediact/coding-standard/src/MediaCT',
                        './vendor/youwe/coding-standard-magento2/src/Magento2',
                        'YouweMagento2'
                    ],
                    './vendor/youwe/testing-suite/config/magento2',
                );
            } elseif ($name === "phpmd.xml") {
                $this->updatePath(
                    $unixFileMapping->getDestination(),
                    [
                        './vendor/mediact/coding-standard-magento2/src/MediactMagento2/phpmd.xml',
                        './vendor/mediact/coding-standard/src/MediaCT/phpmd.xml',
                        './vendor/youwe/coding-standard-magento2/src/Magento2/phpmd.xml',
                        './vendor/youwe/coding-standard-magento2/src/YouweMagento2/phpmd.xml'
                    ],
                    './vendor/youwe/testing-suite/config/magento2/phpmd.xml',
                );
            } elseif ($name === "grumphp.yml") {
                $this->updatePath(
                    $unixFileMapping->getDestination(),
                    [
                        'vendor/mediact/testing-suite/config/default/grumphp.yml',
                        'vendor/youwe/testing-suite/config/default/grumphp.yml',
                    ],
                    'vendor/youwe/testing-suite/config/magento2/grumphp.yml',
                );
            }
        } else {
            if ($name === "phpcs.xml") {
                $this->updatePath(
                    $unixFileMapping->getDestination(),
                    [
                        './vendor/mediact/coding-standard/src/MediaCT',
                    ],
                    './vendor/youwe/coding-standard/src/Global',
                );
            } elseif ($name === "phpmd.xml") {
                $this->updatePath(
                    $unixFileMapping->getDestination(),
                    [
                        './vendor/mediact/coding-standard/src/MediaCT/phpmd.xml',
                        './vendor/youwe/coding-standard-magento2/src/Magento2/phpmd.xml',
                    ],
                    './vendor/youwe/coding-standard/src/Global/phpmd.xml',
                );
            } elseif ($name === "grumphp.yml") {
                $this->updatePath(
                    $unixFileMapping->getDestination(),
                    [
                        'vendor/mediact/testing-suite/config/default/grumphp.yml',
                    ],
                    'vendor/youwe/testing-suite/config/default/grumphp.yml',
                );
            }
        }
    }

    /**
     * @param string $destination
     * @param array  $oldPaths
     * @param string $newPath
     *
     * @return void
     */
    private function updatePath(
        string $destination,
        array $oldPaths,
        string $newPath,
    ): void {
        $file    = file_get_contents($destination);
        $newFile = str_replace(
            $oldPaths,
            $newPath,
            $file,
        );
        file_put_contents($destination, $newFile);
    }
}
