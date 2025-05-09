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
use Youwe\FileMapping\FileMappingInterface;
use Youwe\TestingSuite\Composer\MappingResolver;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ArchiveExcludeInstaller implements InstallerInterface
{
    private readonly JsonFile $file;

    private readonly string|bool $destination;

    /** @var array */
    private $defaults = [
        '/docker-compose.yml',
        '/examples',
        '/example',
        '/.env.dev',
        '/.gitattributes',
        '/.gitignore',
        '/tests'
    ];

    /**
     * Constructor.
     *
     * @param JsonFile|null   $file
     * @param array|null      $defaults
     */
    public function __construct(
        private readonly MappingResolver $resolver,
        private readonly IOInterface $io,
        ?JsonFile $file = null,
        ?string $destination = null,
        ?array $defaults = null
    ) {
        $this->file        = $file ?? new JsonFile(Factory::getComposerFile());
        $this->destination = $destination ?? getcwd();
        $this->defaults    = $defaults ?? $this->defaults;
    }

    /**
     * Install.
     */
    #[Override]
    public function install(): void
    {
        $definition = $this->file->read();
        $excluded   = $definition['archive']['exclude'] ?? [];

        $excluded = array_map(
            fn(string $exclude): string => str_starts_with($exclude, '/')
                ? $exclude
                : '/' . $exclude,
            $excluded
        );

        $files = array_merge(
            $this->defaults,
            array_map(
                fn(FileMappingInterface $mapping): string => '/' . $mapping->getRelativeDestination(),
                iterator_to_array(
                    $this->resolver->resolve()
                )
            )
        );

        foreach ($files as $file) {
            if (
                !in_array($file, $excluded)
                && file_exists($this->destination . $file)
            ) {
                $excluded[] = $file;
                $this->io->write(
                    sprintf(
                        '<info>Added:</info> %s to archive exclude in composer.json',
                        $file
                    )
                );
            }
        }

        $definition['archive']['exclude'] = $excluded;
        $this->file->write($definition);
    }
}
