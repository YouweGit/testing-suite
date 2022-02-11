<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests\Installer;

use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use PHPUnit\Framework\TestCase;
use Youwe\TestingSuite\Composer\ConfigResolver;
use Youwe\TestingSuite\Composer\Installer\ConfigInstaller;

/**
 * @coversDefaultClass \Youwe\TestingSuite\Composer\Installer\ConfigInstaller
 * @SuppressWarnings(PHPMD)
 */
class ConfigInstallerTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::install
     */
    public function testInstall(): void
    {
        $resolver = $this->createMock(ConfigResolver::class);
        $file     = $this->createMock(JsonFile::class);

        $installer = new ConfigInstaller($resolver, $file);

        $resolverOutput = [
            'sort-packages' => true
        ];

        $configWrite = [
            'config' => $resolverOutput
        ];

        $file
            ->expects(self::once())
            ->method('read')
            ->willReturn([]);

        $resolver
            ->expects(self::once())
            ->method('resolve')
            ->willReturn($resolverOutput);

        $file
            ->expects(self::once())
            ->method('write')
            ->with($configWrite);

        $installer->install();
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                [],
                [
                    'sort-packages' => true
                ]
            ],
            [
                [],
                [
                    'extra' => [
                        'grumphp' => [
                            'config-default-path' => 'vendor/mediact/testing-suite/config/default/grumphp.yml'
                        ]
                    ]
                ]
            ]
        ];
    }
}
