<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Installer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Youwe\Composer\DependencyInstaller\DependencyInstaller;
use Youwe\TestingSuite\Composer\MappingResolver;
use Youwe\TestingSuite\Composer\ProjectTypeResolver;

/**
 * @SuppressWarnings("PHPMD.ShortVariable")
 */
class PackagesInstaller implements InstallerInterface
{
    /** @var DependencyInstaller */
    private $installer;

    /** @var Composer */
    private $composer;

    /** @var ProjectTypeResolver */
    private $typeResolver;

    /** @var IOInterface */
    private $io;

    /** @var array */
    public $mapping = [
        MappingResolver::DEFAULT_MAPPING_TYPE => [
            'phpunit/phpunit' => [
                'version' => '@stable',
                'updateDependencies' => true,
                'allowVersionOverride' => false,
            ],
        ],
        'magento2' => [
            'phpstan/extension-installer' => [
                'version' => '^1.3',
                'updateDependencies' => true,
            ],
            'bitexpert/phpstan-magento' => [
                'version' => '~0.30',
                'updateDependencies' => true,
            ],
            'magento/magento-coding-standard' => [
                'version' => '@stable',
                'updateDependencies' => true,
            ]
        ],
    ];

    /**
     * Constructor.
     *
     * @param Composer                 $composer
     * @param ProjectTypeResolver      $typeResolver
     * @param IOInterface              $io
     * @param DependencyInstaller|null $installer
     * @param array|null               $mapping
     */
    public function __construct(
        Composer $composer,
        ProjectTypeResolver $typeResolver,
        IOInterface $io,
        ?DependencyInstaller $installer = null,
        ?array $mapping = null,
    ) {
        $this->composer = $composer;
        $this->typeResolver = $typeResolver;
        $this->io = $io;
        $this->installer = $installer ?? new DependencyInstaller();
        $this->mapping = $mapping ?? $this->mapping;
    }

    /**
     * Install.
     *
     * @return void
     */
    public function install(): void
    {
        $type = $this->typeResolver->resolve();
        $projectTypePackages = $this->mapping[$type] ?? [];
        $packagesToInstall = array_replace_recursive(
            $this->mapping[MappingResolver::DEFAULT_MAPPING_TYPE],
            $projectTypePackages,
        );

        foreach ($packagesToInstall as $name => $package) {
            if (!$this->isPackageRequired($name, $package['version'])) {
                $this->io->write(
                    sprintf('Requiring package %s', $name)
                );

                $this->installer->installPackage(
                    $name,
                    $package['version'],
                    $package['dev'] ?? true,
                    $package['updateDependencies'] ?? false,
                    $package['allowVersionOverride'] ?? true,
                );
            }
        }
    }

    /**
     * Whether a package has been required.
     *
     * @param string $packageName
     * @param string $version
     *
     * @return bool
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
