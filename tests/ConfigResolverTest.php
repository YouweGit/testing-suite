<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Youwe\TestingSuite\Composer\ConfigResolver;
use Youwe\TestingSuite\Composer\ProjectTypeResolver;

/**
 * @coversDefaultClass \Youwe\TestingSuite\Composer\ConfigResolver
 * @SuppressWarnings(PHPMD)
 */
class ConfigResolverTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::resolve
     */
    public function testResolve(): void
    {
        $jsonFile   = 'default.json';
        $jsonData   = '{"sort-packages": true}';
        $filesystem = vfsStream::setup(
            sha1(__METHOD__),
            null,
            [$jsonFile => $jsonData]
        );
        $template   = $filesystem->url() . '/%s.json';

        $typeResolver = $this->createMock(ProjectTypeResolver::class);

        $resolver = new ConfigResolver(
            $typeResolver,
            $template
        );

        $typeResolver
            ->expects(self::once())
            ->method('resolve')
            ->willReturn($filesystem->url() . '/' . $jsonFile);

        $result = $resolver->resolve();

        $this->assertSame(json_decode($jsonData, true), $result);
    }
}
