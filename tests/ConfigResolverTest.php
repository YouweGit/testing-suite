<?php

/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Youwe\PHPTestingSuite\Composer\Tests;

use Youwe\PHPTestingSuite\Composer\ProjectTypeResolver;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Youwe\PHPTestingSuite\Composer\ConfigResolver;

/**
 * @coversDefaultClass \Youwe\PHPTestingSuite\Composer\ConfigResolver
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
