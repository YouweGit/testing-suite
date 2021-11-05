<?php

/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Youwe\PHPTestingSuite\Composer\Tests\Installer;

use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Youwe\PHPTestingSuite\Composer\ConfigResolver;
use PHPUnit\Framework\TestCase;
use Youwe\PHPTestingSuite\Composer\Installer\ConfigInstaller;

/**
 * @coversDefaultClass \Youwe\PHPTestingSuite\Composer\Installer\ConfigInstaller
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
        $io       = $this->createMock(IOInterface::class);
        $file     = $this->createMock(JsonFile::class);

        $installer = new ConfigInstaller($resolver, $io, $file);

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
