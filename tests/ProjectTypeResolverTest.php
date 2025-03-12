<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests;

use Composer\Composer;
use Composer\Config;
use Composer\Package\RootPackageInterface;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Youwe\TestingSuite\Composer\ProjectTypeResolver;

#[CoversMethod(ProjectTypeResolver::class, '__construct')]
#[CoversMethod(ProjectTypeResolver::class, 'resolve')]
class ProjectTypeResolverTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[TestWith(['some-type', 'default'])]
    #[TestWith(['magento-module', 'magento1'])]
    #[TestWith(['magento2-module', 'magento2'])]
    #[TestWith(['alumio-project', 'alumio'])]
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
}
