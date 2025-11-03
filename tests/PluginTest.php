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
use Composer\Script\Event;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Youwe\TestingSuite\Composer\Installer\InstallerInterface;
use Youwe\TestingSuite\Composer\Installer\PostPackageChangeInstallerInterface;
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
     * @SuppressWarnings("PHPMD.LongVariable")
     */
    public function testActivate(): void
    {
        $plugin = new Plugin();
        $plugin->activate(
            $this->createMock(Composer::class),
            $this->createMock(IOInterface::class),
        );

        $isPackageChangedProperty = new ReflectionProperty($plugin, 'isThisPackageChanged');
        $this->assertFalse($isPackageChangedProperty->getValue($plugin));

        $installersProperty = new ReflectionProperty(Plugin::class, 'installers');
        foreach ($installersProperty->getValue($plugin) as $installer) {
            $this->assertTrue(
                condition: $installer instanceof InstallerInterface
                    || $installer instanceof PostPackageChangeInstallerInterface,
                message: 'Installer should be InstallerInterface|PostPackageChangeInstallerInterface',
            );
        }
    }

    /**
     * @throws Exception
     * @SuppressWarnings("PHPMD.LongVariable")
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
        bool $isYouweTestingSuiteChange,
    ): void {
        $postInstallCmdInstaller = $this->createMock(InstallerInterface::class);
        $postPackageChangeInstaller = $this->createMock(PostPackageChangeInstallerInterface::class);

        // It never runs the normal installers (which are executed post-install-cmd)
        $postInstallCmdInstaller
            ->expects(self::never())
            ->method('install');
        // It only runs the post-package-change installers when the applied change is youwe/testing-suite
        $postPackageChangeInstaller
            ->expects($isYouweTestingSuiteChange ? self::once() : self::never())
            ->method('installPostPackageChange');

        $plugin = new Plugin($postInstallCmdInstaller, $postPackageChangeInstaller);

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
        $io->expects($isYouweTestingSuiteChange ? self::once() : self::never())
            ->method('write')
            ->with('<info>Running Youwe Testing Suite pre-installer</info>');

        $event->expects(self::any())
            ->method('getIO')
            ->willReturn($io);

        $plugin->onPackageChange($event);

        $property = new ReflectionProperty($plugin, 'isThisPackageChanged');
        $this->assertSame($isYouweTestingSuiteChange, $property->getValue($plugin));
    }

    /**
     * @throws Exception
     * @SuppressWarnings("PHPMD.LongVariable")
     */
    #[TestWith(data: [true], name: 'It runs installers when applied change contains Testing Suite')]
    #[TestWith(data: [false], name: 'It doesn\'t run installers when installing/updating something else')]
    public function testOnPostInstall(bool $isYouweTestingSuiteChange): void
    {
        $postInstallCmdInstaller = $this->createMock(InstallerInterface::class);
        $postPackageChangeInstaller = $this->createMock(PostPackageChangeInstallerInterface::class);

        // It only never runs the normal installers when $isThisPackageChanged
        $postInstallCmdInstaller
            ->expects($isYouweTestingSuiteChange ? self::once() : self::never())
            ->method('install');
        // It never runs the post-package-change installers in this stage
        $postPackageChangeInstaller
            ->expects(self::never())
            ->method('installPostPackageChange');

        $plugin = new Plugin($postInstallCmdInstaller, $postPackageChangeInstaller);
        $property = new ReflectionProperty($plugin, 'isThisPackageChanged');
        $property->setValue($plugin, $isYouweTestingSuiteChange);

        $io = $this->createMock(IOInterface::class);
        $io->expects($isYouweTestingSuiteChange ? self::once() : self::never())
            ->method('write')
            ->with('<info>Running Youwe Testing Suite installer</info>');

        $event = $this->createMock(Event::class);
        $event->expects(self::any())
            ->method('getIO')
            ->willReturn($io);

        $plugin->onPostInstall($event);
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
