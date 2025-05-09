<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests;

use Composer\Composer;
use Composer\IO\IOInterface;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Youwe\TestingSuite\Composer\Installer\InstallerInterface;
use Youwe\TestingSuite\Composer\Plugin;

/**
 * @coversDefaultClass \Youwe\TestingSuite\Composer\Plugin
 * @SuppressWarnings(PHPMD)
 */
class PluginTest extends TestCase
{
    /**
     *
     * @covers ::activate
     * @covers ::addInstallers
     */
    public function testActivate(): void
    {
        $plugin = new Plugin();
        $plugin->activate(
            $this->createMock(Composer::class),
            $this->createMock(IOInterface::class)
        );

        $reflection = new ReflectionProperty(Plugin::class, 'installers');
        $reflection->setAccessible(true);

        $this->assertContainsOnlyInstancesOf(
            InstallerInterface::class,
            $reflection->getValue($plugin)
        );
    }

    /**
     *
     * @covers ::__construct
     * @covers ::install
     */
    public function testInstall(): void
    {
        $installers = [
            $this->createMock(InstallerInterface::class),
            $this->createMock(InstallerInterface::class)
        ];

        foreach ($installers as $installer) {
            $installer
                ->expects(self::once())
                ->method('install');
        }

        $plugin = new Plugin(...$installers);
        $plugin->install();
    }

    /**
     * @covers ::getSubscribedEvents
     */
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
