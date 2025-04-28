<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests\Installer;

use Composer\Composer;
use Composer\IO\IOInterface;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Youwe\Composer\DependencyInstaller\DependencyInstaller;
use Youwe\TestingSuite\Composer\Installer\PackagesInstaller;
use Youwe\TestingSuite\Composer\MappingResolver;
use Youwe\TestingSuite\Composer\ProjectTypeResolver;

/**
 * @phpcs:disable GlobalPhpUnit.Coverage.CoversTag.CoversTagMissing
 */
#[CoversMethod(PackagesInstaller::class, '__construct')]
#[CoversMethod(PackagesInstaller::class, 'install')]
#[CoversMethod(PackagesInstaller::class, 'isPackageRequired')]
class PackagesInstallerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCanInstallWithMocks(): void
    {
        $composerMock = $this->createMock(Composer::class);
        $typeRresolverMock = $this->createMock(ProjectTypeResolver::class);
        $depInstallerMock = $this->createMock(DependencyInstaller::class);
        $ioMock = $this->createMock(IOInterface::class);

        $installer = new PackagesInstaller(
            $composerMock,
            $typeRresolverMock,
            $ioMock,
            $depInstallerMock,
        );

        $depInstallerMock
            ->expects($this->atLeastOnce())
            ->method('installPackage');

        $installer->install();
    }

    /**
     * @throws Exception
     */
    public function testCanMergeDefaultPackagesWhenInstalling(): void
    {
        $composerMock = $this->createMock(Composer::class);
        $typeResolverMock = $this->createMock(ProjectTypeResolver::class);
        $depInstallerMock = $this->createMock(DependencyInstaller::class);
        $ioMock = $this->createMock(IOInterface::class);

        // Simulate magento 1 project
        $typeResolverMock
            ->method('resolve')
            ->willReturn('foobar');

        $mapping = [
            MappingResolver::DEFAULT_MAPPING_TYPE => [
                'phpunit/phpunit' => [
                    'version' => '@stable',
                    'dev' => true,
                    'updateDependencies' => true,
                    'allowVersionOverride' => false,
                ],
            ],
            // Fictional package to test merging for the 'foobar' project type.
            'foobar' => [
                'foo/bar-foobar' => [
                    'version' => '^1.3.0',
                    'dev' => true,
                    'updateDependencies' => false,
                    'allowVersionOverride' => true,
                ],
            ],
        ];

        $installer = new PackagesInstaller(
            $composerMock,
            $typeResolverMock,
            $ioMock,
            $depInstallerMock,
            $mapping
        );

        $depInstallerMock
            ->expects($this->exactly(2))
            ->method('installPackage')
            ->willReturnCallback(
                function ($package, $version, $dev, $updateDependencies, $allowVersionOverride) {
                    static $calls = 0;
                    $calls++;

                    if ($calls === 1) {
                        $this->assertEquals('phpunit/phpunit', $package);
                        $this->assertEquals('@stable', $version);
                        $this->assertTrue($dev);
                        $this->assertTrue($updateDependencies);
                        $this->assertFalse($allowVersionOverride);
                    }

                    if ($calls === 2) {
                        $this->assertEquals('foo/bar-foobar', $package);
                        $this->assertEquals('^1.3.0', $version);
                        $this->assertTrue($dev);
                        $this->assertFalse($updateDependencies);
                        $this->assertTrue($allowVersionOverride);
                    }

                    if ($calls > 2) {
                        $this->fail('Unexpected number of calls');
                    }
                }
            );

        $installer->install();
    }

    public function testCanMergeRecursivelyWhenInstalling(): void
    {
        $composerMock = $this->createMock(Composer::class);
        $typeResolverMock = $this->createMock(ProjectTypeResolver::class);
        $depInstallerMock = $this->createMock(DependencyInstaller::class);
        $ioMock = $this->createMock(IOInterface::class);

        $mapping = [
            MappingResolver::DEFAULT_MAPPING_TYPE => [
                'phpunit/phpunit' => [
                    'version' => '@stable',
                    'dev' => true,
                    'updateDependencies' => true,
                    'allowVersionOverride' => false,
                ],
            ],
            'magento2' => [
                'phpunit/phpunit' => [
                    'version' => '^10.6.5',
                    'dev' => false,
                    'allowVersionOverride' => true,
                ],
            ],
        ];

        $typeResolverMock
            ->method('resolve')
            ->willReturn('magento2');

        $installer = new PackagesInstaller(
            $composerMock,
            $typeResolverMock,
            $ioMock,
            $depInstallerMock,
            $mapping,
        );

        $depInstallerMock
            ->expects($this->exactly(1))
            ->method('installPackage')
            ->with('phpunit/phpunit', '^10.6.5', false, true, true);

        $installer->install();
    }

    public function testPhpUnitIsInstalledForUnknownProjectType(): void
    {
        $composerMock = $this->createMock(Composer::class);
        $typeResolverMock = $this->createMock(ProjectTypeResolver::class);
        $depInstallerMock = $this->createMock(DependencyInstaller::class);
        $ioMock = $this->createMock(IOInterface::class);

        $typeResolverMock
            ->method('resolve')
            ->willReturn('foobar');

        $installer = new PackagesInstaller(
            $composerMock,
            $typeResolverMock,
            $ioMock,
            $depInstallerMock,
        );

        $depInstallerMock
            ->expects($this->exactly(1))
            ->method('installPackage')
            ->with('phpunit/phpunit', '@stable', true, true, false);

        $installer->install();
    }
}
