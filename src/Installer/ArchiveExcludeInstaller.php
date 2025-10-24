<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Installer;

use Composer\IO\IOInterface;
use Exception;
use Youwe\FileMapping\FileMappingInterface;
use Youwe\TestingSuite\Composer\ComposerJsonWriter;
use Youwe\TestingSuite\Composer\MappingResolver;

/**
 * @SuppressWarnings("PHPMD.ShortVariable")
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
class ArchiveExcludeInstaller implements InstallerInterface
{
    private const DEFAULTS = [
        '/docker-compose.yml',
        '/examples',
        '/example',
        '/.env.dev',
        '/.gitattributes',
        '/.gitignore',
        '/tests',
    ];

    private readonly string $destination;
    private readonly array $defaults;

    /**
     * Constructor.
     *
     * @param MappingResolver $resolver
     * @param IOInterface $io
     * @param ComposerJsonWriter $composerJsonWriter
     * @param string |null $destination
     * @param array|null $defaults
     */
    public function __construct(
        private readonly MappingResolver $resolver,
        private readonly IOInterface $io,
        private readonly ComposerJsonWriter $composerJsonWriter,
        ?string $destination = null,
        ?array $defaults = null,
    ) {
        $this->destination = $destination ?? getcwd();
        $this->defaults = $defaults ?? self::DEFAULTS;
    }

    /**
     * Install.
     *
     * @return void
     * @throws Exception
     */
    public function install(): void
    {
        $definition = $this->composerJsonWriter->getContents();
        $excluded = $definition->archive->exclude ?? [];

        $excluded = array_map(
            function (string $exclude): string {
                return substr($exclude, 0, 1) !== '/'
                    ? '/' . $exclude
                    : $exclude;
            },
            $excluded,
        );

        $files = array_merge(
            $this->defaults,
            array_map(
                function (FileMappingInterface $mapping): string {
                    return '/' . $mapping->getRelativeDestination();
                },
                iterator_to_array(
                    $this->resolver->resolve(),
                ),
            ),
        );

        $hasChanges = false;
        foreach ($files as $file) {
            if (
                !in_array($file, $excluded)
                && file_exists($this->destination . $file)
            ) {
                $excluded[] = $file;
                $hasChanges = true;
                $this->io->write(
                    sprintf(
                        '<info>Added:</info> %s to archive exclude in composer.json',
                        $file,
                    ),
                );
            }
        }

        if (!$hasChanges) {
            return;
        }

        if (!isset($definition->archive)) {
            $definition->archive = (object) [];
        }
        $definition->archive->exclude = $excluded;

        $this->composerJsonWriter->setContents($definition);
    }
}
