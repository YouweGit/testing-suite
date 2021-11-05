<?php

/**
 * Copyright Mediact. All rights reserved.
 * https://www.Mediact.nl
 */

namespace Youwe\PHPTestingSuite\Composer\Tests;

use Composer\Composer;
use Composer\IO\IOInterface;
use Youwe\PHPTestingSuite\Composer\Installer\InstallerInterface;
use Youwe\PHPTestingSuite\Composer\Plugin;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * @coversDefaultClass \Youwe\PHPTestingSuite\Composer\Plugin
 * @SuppressWarnings(PHPMD)
 */
class PluginTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::activate
     * @covers ::addInstallers
     */
    public function testActivate()
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
     * @return void
     *
     * @covers ::__construct
     * @covers ::install
     */
    public function testInstall()
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
     * @return void
     *
     * @covers ::getSubscribedEvents
     */
    public function testGetSubscribesEvents()
    {
        $plugin = new Plugin();

        foreach (Plugin::getSubscribedEvents() as $event => $methods) {
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($plugin, $method));
            }
        }
    }
}
