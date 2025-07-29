<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests\Installer;

use Composer\Json\JsonFile;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Youwe\TestingSuite\Composer\ConfigResolver;
use Youwe\TestingSuite\Composer\Installer\ConfigInstaller;

/**
 * @phpcs:disable GlobalPhpUnit.Coverage.CoversTag.CoversTagMissing
 */
#[CoversMethod(ConfigInstaller::class, '__construct')]
#[CoversMethod(ConfigInstaller::class, 'install')]
class ConfigInstallerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testInstall(): void
    {
        $resolver = $this->createMock(ConfigResolver::class);
        $file     = $this->createMock(JsonFile::class);

        $installer = new ConfigInstaller($resolver, $file);

        $resolverOutput = [
            'sort-packages' => true,
        ];

        $configWrite = [
            'config' => $resolverOutput,
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
                ],
            ],
            [
                [],
                [
                    'extra' => [
                        'grumphp' => [
                            'config-default-path' => 'vendor/youwe/testing-suite/config/default/grumphp.yml'
                        ],
                    ],
                ],
            ],
        ];
    }
}
