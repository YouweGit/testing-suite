<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Installer;

use Override;
use Composer\Composer;
use Composer\IO\IOInterface;
use Youwe\Composer\DependencyInstaller\DependencyInstaller;
use Youwe\TestingSuite\Composer\MappingResolver;
use Youwe\TestingSuite\Composer\ProjectTypeResolver;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class PackagesInstaller implements InstallerInterface
{
    /** @var array */
    private $mapping = [
        MappingResolver::DEFAULT_MAPPING_TYPE => [],
        'magento1' => [
            [
                'name' => 'youwe/coding-standard-magento1',
                'version' => '^1.3.0',
                'dev' => true
            ]
        ],
        'magento2' => [
            [
                'name' => 'youwe/coding-standard-magento2',
                'version' => '^2.0.0',
                'dev' => true
            ],
            [
                'name' => 'phpstan/extension-installer',
                'version' => '^1.3',
                'dev' => true
            ],
            [
                'name' => 'bitexpert/phpstan-magento',
                'version' => '~0.30',
                'dev' => true
            ],
        ],
        'laravel' => [
            [
                'name' => 'elgentos/laravel-coding-standard',
                'version' => '^1.0.0',
                'dev' => true
            ]
        ]
    ];

    /**
     * Constructor.
     *
     * @param DependencyInstaller|null $installer
     * @param array|null               $mapping
     */
    public function __construct(
        private readonly Composer $composer,
        private readonly ProjectTypeResolver $typeResolver,
        private readonly IOInterface $io,
        private readonly DependencyInstaller $installer = new DependencyInstaller(),
        ?array $mapping = null
    ) {
        $this->mapping      = $mapping ?? $this->mapping;
    }

    /**
     * Install.
     */
    #[Override]
    public function install(): void
    {
        $type = $this->typeResolver->resolve();
        if (!isset($this->mapping[$type])) {
            return;
        }

        foreach ($this->mapping[$type] as $package) {
            if (!$this->isPackageRequired($package['name'], $package['version'])) {
                $this->io->write(
                    sprintf('Requiring package %s', $package['name'])
                );

                $this->installer->installPackage(
                    $package['name'],
                    $package['version']
                );
            }
        }
    }

    /**
     * Whether a package has been required.
     *
     *
     */
    private function isPackageRequired(string $packageName, string $version): bool
    {
        foreach ($this->composer->getPackage()->getRequires() as $require) {
            if ($require->getTarget() === $packageName && $require->getPrettyConstraint() === $version) {
                return true;
            }
        }

        foreach ($this->composer->getPackage()->getDevRequires() as $devRequire) {
            if ($devRequire->getTarget() === $packageName && $devRequire->getPrettyConstraint() === $version) {
                return true;
            }
        }

        return false;
    }
}
