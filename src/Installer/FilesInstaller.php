<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Installer;

use Override;
use Composer\IO\IOInterface;
use Youwe\Composer\FileInstaller as ComposerFileInstaller;
use Youwe\FileMapping\FileMappingInterface;
use Youwe\TestingSuite\Composer\MappingResolver;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class FilesInstaller implements InstallerInterface
{
    /**
     * Constructor.
     */
    public function __construct(private readonly MappingResolver $mappingResolver, private readonly ComposerFileInstaller $fileInstaller, private readonly IOInterface $io)
    {
    }

    /**
     * Install.
     */
    #[Override]
    public function install(): void
    {
        foreach ($this->mappingResolver->resolve() as $mapping) {
            if (file_exists($mapping->getDestination())) {
                $this->resolveYouwePathing($mapping);
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

    /**
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     *
     */
    private function resolveYouwePathing(FileMappingInterface $unixFileMapping): void
    {
        $name = $unixFileMapping->getRelativeDestination();

        if ($this->mappingResolver->getTypeResolver()->resolve() === 'magento2') {
            if ($name === "phpcs.xml") {
                $this->updatePath(
                    $unixFileMapping->getDestination(),
                    [
                        './vendor/mediact/coding-standard-magento2/src/MediactMagento2',
                        './vendor/mediact/coding-standard/src/MediaCT',
                        './vendor/youwe/coding-standard-magento2/src/Magento2'
                    ],
                    'YouweMagento2'
                );
            } elseif ($name === "phpmd.xml") {
                $this->updatePath(
                    $unixFileMapping->getDestination(),
                    [
                        './vendor/mediact/coding-standard-magento2/src/MediactMagento2/phpmd.xml',
                        './vendor/mediact/coding-standard/src/MediaCT/phpmd.xml',
                        './vendor/youwe/coding-standard-magento2/src/Magento2/phpmd.xml'
                    ],
                    './vendor/youwe/coding-standard-magento2/src/YouweMagento2/phpmd.xml'
                );
            } elseif ($name === "grumphp.yml") {
                $this->updatePath(
                    $unixFileMapping->getDestination(),
                    [
                        'vendor/mediact/testing-suite/config/default/grumphp.yml',
                        'vendor/youwe/testing-suite/config/default/grumphp.yml'
                    ],
                    'vendor/youwe/testing-suite/config/magento2/grumphp.yml'
                );
            }
        } elseif ($this->mappingResolver->getTypeResolver()->resolve() === 'magento') {
            if ($name === "phpcs.xml") {
                $this->updatePath(
                    $unixFileMapping->getDestination(),
                    ['./vendor/mediact/coding-standard-magento1/src/MediactMagento1'],
                    './vendor/youwe/coding-standard-magento1/src/Magento1'
                );
            }
        } elseif ($name === "phpcs.xml") {
            $this->updatePath(
                $unixFileMapping->getDestination(),
                ['./vendor/mediact/coding-standard/src/MediaCT'],
                './vendor/youwe/coding-standard/src/Global'
            );
        } elseif ($name === "phpmd.xml") {
            $this->updatePath(
                $unixFileMapping->getDestination(),
                [
                    './vendor/mediact/coding-standard/src/MediaCT/phpmd.xml',
                    './vendor/youwe/coding-standard-magento2/src/Magento2/phpmd.xml'
                ],
                './vendor/youwe/coding-standard/src/Global/phpmd.xml'
            );
        } elseif ($name === "grumphp.yml") {
            $this->updatePath(
                $unixFileMapping->getDestination(),
                ['vendor/mediact/testing-suite/config/default/grumphp.yml'],
                'vendor/youwe/testing-suite/config/default/grumphp.yml'
            );
        }
    }

    
    private function updatePath(
        string $destination,
        array $oldPaths,
        string $newPath
    ): void {
        $file    = file_get_contents($destination);
        $newFile = str_replace(
            $oldPaths,
            $newPath,
            $file
        );
        file_put_contents($destination, $newFile);
    }
}
