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
    #[TestWith(data: ['some-type', 'default'], name: 'some-type')]
    #[TestWith(data: ['drupal-bundle', 'drupal'], name: 'drupal-bundle')]
    #[TestWith(data: ['drupal-project', 'drupal'], name: 'drupal-project')]
    #[TestWith(data: ['magento-project', 'magento2'], name: 'magento-project')]
    #[TestWith(data: ['magento2-module', 'magento2'], name: 'magento2-module')]
    #[TestWith(data: ['magento2-project', 'magento2'], name: 'magento2-project')]
    #[TestWith(data: ['pimcore-bundle', 'pimcore'], name: 'pimcore-bundle')]
    #[TestWith(data: ['pimcore-project', 'pimcore'], name: 'pimcore-project')]
    public function testToString(string $packageType, string $expected): void
    {
        $composer = $this->createMock(Composer::class);
        $package  = $this->createMock(RootPackageInterface::class);

        $composer
            ->expects(self::atLeastOnce())
            ->method('getPackage')
            ->willReturn($package);

        $package
            ->expects(self::atLeastOnce())
            ->method('getExtra')
            ->willReturn([]);

        $package
            ->expects(self::once())
            ->method('getType')
            ->willReturn($packageType);

        $decider = new ProjectTypeResolver($composer);
        $this->assertEquals($expected, $decider->resolve());
    }

    #[TestWith(data: ['drupal'], name: 'drupal')]
    #[TestWith(data: ['magento2'], name: 'magento2')]
    #[TestWith(data: ['pimcore'], name: 'pimcore')]
    public function testToStringOverwrite($type): void
    {
        $composer = $this->createMock(Composer::class);
        $package = $this->createMock(RootPackageInterface::class);

        $composer
            ->expects(self::any())
            ->method('getPackage')
            ->willReturn($package);

        $package
            ->expects(self::any())
            ->method('getExtra')
            ->willReturn(['youwe-testing-suite' => ['type' => $type]]);

        $decider = new ProjectTypeResolver($composer);
        $this->assertEquals($type, $decider->resolve());
    }
}
