<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests\Installer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\Link;
use Composer\Package\Package;
use PHPUnit\Framework\TestCase;
use Youwe\Composer\DependencyInstaller\DependencyInstaller;
use Youwe\TestingSuite\Composer\Installer\PackagesInstaller;
use Youwe\TestingSuite\Composer\ProjectTypeResolver;

/**
 * @coversDefaultClass \Youwe\TestingSuite\Composer\Installer\PackagesInstaller
 * @SuppressWarnings(PHPMD)
 */
class PackagesInstallerTest extends TestCase
{
    /**
     *
     * @dataProvider dataProvider
     *
     * @covers ::__construct
     * @covers ::install
     * @covers ::isPackageRequired
     */
    public function testInstall(
        string $type,
        array $requires,
        ?array $expected = null
    ): void {
        $composer     = $this->createMock(Composer::class);
        $typeResolver = $this->createMock(ProjectTypeResolver::class);
        $depInstaller = $this->createMock(DependencyInstaller::class);
        $io           = $this->createMock(IOInterface::class);

        $typeResolver
            ->expects(self::any())
            ->method('resolve')
            ->willReturn($type);

        $installer = new PackagesInstaller(
            $composer,
            $typeResolver,
            $io,
            $depInstaller
        );

        if ($expected) {
            $depInstaller
                ->expects(self::exactly(count($expected)))
                ->method('installPackage')
                ->withConsecutive(...$expected);
        } else {
            $depInstaller
                ->expects(self::never())
                ->method('installPackage');
        }

        $installer->install();
    }

    public function dataProvider(): array
    {
        return [
            [
                'magento1',
                $this->createLinkMocks(['foo/bar']),
                [['youwe/coding-standard-magento1']]
            ],
            [
                'magento1',
                $this->createLinkMocks(
                    ['foo/bar', 'youwe/coding-standard-magento1']
                ),
                [['youwe/coding-standard-magento1']]
            ],
            [
                'magento2',
                $this->createLinkMocks(['foo/bar']),
                [['youwe/coding-standard-magento2']]
            ],
            [
                'default',
                $this->createLinkMocks(['foo/bar']),
                null
            ],
            [
                'unknown',
                $this->createLinkMocks(['foo/bar']),
                null
            ]
        ];
    }

    /**
     * @param string[] $targets
     *
     * @return Link[]
     */
    private function createLinkMocks(array $targets): array
    {
        return array_map(
            function (string $target): Link {
                /** @var Link $mock */
                $mock = $this->createConfiguredMock(
                    Link::class,
                    ['getTarget' => $target]
                );

                return $mock;
            },
            $targets
        );
    }
}
