<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com changes done  
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests;

use Composer\Composer;
use Composer\Config;
use Composer\Package\RootPackageInterface;
use PHPUnit\Framework\TestCase;
use Youwe\TestingSuite\Composer\ProjectTypeResolver;

/**
 * @coversDefaultClass \Youwe\TestingSuite\Composer\ProjectTypeResolver
 */
class ProjectTypeResolverTest extends TestCase
{
    /**
     * @param string $packageType
     * @param string $expected
     *
     * @return void
     *
     * @dataProvider dataProvider
     *
     * @covers ::__construct
     * @covers ::resolve
     */
    public function testToString(string $packageType, string $expected): void
    {
        $composer = $this->createMock(Composer::class);
        $package  = $this->createMock(RootPackageInterface::class);
        $config   = $this->createMock(Config::class);

        $composer
            ->expects(self::once())
            ->method('getPackage')
            ->willReturn($package);

        $composer
            ->expects(self::once())
            ->method('getConfig')
            ->willReturn($config);

        $package
            ->expects(self::once())
            ->method('getType')
            ->willReturn($packageType);

        $decider = new ProjectTypeResolver($composer);
        $this->assertEquals($expected, $decider->resolve());
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::resolve
     */
    public function testToStringOverwrite(): void
    {
        $composer = $this->createMock(Composer::class);
        $config   = $this->createMock(Config::class);

        $composer
            ->expects(self::never())
            ->method('getPackage');

        $composer
            ->expects(self::once())
            ->method('getConfig')
            ->willReturn($config);

        $config
            ->expects(self::once())
            ->method('has')
            ->with('youwe-testing-suite')
            ->willReturn(true);

        $config
            ->expects(self::once())
            ->method('get')
            ->with('youwe-testing-suite')
            ->willReturn(['type' => 'magento2']);

        $decider = new ProjectTypeResolver($composer);
        $this->assertEquals('magento2', $decider->resolve());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            ['some-type', 'default'],
            ['magento-module', 'magento1'],
            ['magento2-module', 'magento2'],
            ['alumio-project', 'alumio'],
        ];
    }
}
