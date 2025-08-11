<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests;

use Composer\Composer;
use Composer\DependencyResolver\Operation;
use Composer\Installer\PackageEvent;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Youwe\TestingSuite\Composer\Installer\InstallerInterface;
use Youwe\TestingSuite\Composer\Plugin;

/**
 * @phpcs:disable GlobalPhpUnit.Coverage.CoversTag.CoversTagMissing
 */
#[CoversMethod(Plugin::class, 'activate')]
#[CoversMethod(Plugin::class, 'install')]
#[CoversMethod(Plugin::class, 'getSubscribedEvents')]
class PluginTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testActivate(): void
    {
        $plugin = new Plugin();
        $plugin->activate(
            $this->createMock(Composer::class),
            $this->createMock(IOInterface::class),
        );

        $reflection = new ReflectionProperty(Plugin::class, 'installers');
        $reflection->setAccessible(true);

        $this->assertContainsOnlyInstancesOf(
            InstallerInterface::class,
            $reflection->getValue($plugin),
        );
    }

    /**
     * @throws Exception
     */
    #[TestWith(
        data: [Operation\InstallOperation::class, 'getPackage', 'youwe/testing-suite', true],
        name: 'It runs installers when installing Testing Suite',
    )]
    #[TestWith(
        data: [Operation\UpdateOperation::class, 'getTargetPackage', 'youwe/testing-suite', true],
        name: 'It runs installers when updating Testing Suite',
    )]
    #[TestWith(
        data: [Operation\InstallOperation::class, 'getPackage', 'youwe/coding-standard-phpstorm', false],
        name: 'It doesn\'t run installers when installing something else',
    )]
    #[TestWith(
        data: [Operation\UpdateOperation::class, 'getTargetPackage', 'youwe/coding-standard-phpstorm', false],
        name: 'It doesn\'t run installers when updating something else',
    )]
    public function testOnPackageChange(
        string $operationClass,
        string $packageGetter,
        string $packageName,
        bool $itRunsInstallers,
    ): void {
        $installers = [
            $this->createMock(InstallerInterface::class),
            $this->createMock(InstallerInterface::class)
        ];

        foreach ($installers as $installer) {
            $installer
                ->expects($itRunsInstallers ? self::once() : self::never())
                ->method('install');
        }

        $plugin = new Plugin(...$installers);

        $package = $this->createMock(PackageInterface::class);
        $package->expects(self::once())
            ->method('getName')
            ->willReturn($packageName);

        $operation = $this->createMock($operationClass);
        $operation->expects(self::once())
            ->method($packageGetter)
            ->willReturn($package);

        $event = $this->createMock(PackageEvent::class);
        $event->expects(self::once())
            ->method('getOperation')
            ->willReturn($operation);

        $io = $this->createMock(IOInterface::class);
        $io->expects($itRunsInstallers ? self::once() : self::never())
            ->method('write')
            ->with('<info>Running Youwe Testing Suite installer</info>');

        $event->expects(self::any())
            ->method('getIO')
            ->willReturn($io);

        $plugin->onPackageChange($event);
    }

    public function testGetSubscribesEvents(): void
    {
        $plugin = new Plugin();

        foreach (Plugin::getSubscribedEvents() as $methods) {
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($plugin, $method));
            }
        }
    }
}
