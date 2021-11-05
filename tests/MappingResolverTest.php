<?php

/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Youwe\PHPTestingSuite\Composer\Tests;

use Mediact\FileMapping\FileMappingReaderInterface;
use Youwe\PHPTestingSuite\Composer\ProjectTypeResolver;
use PHPUnit\Framework\TestCase;
use Youwe\PHPTestingSuite\Composer\MappingResolver;

/**
 * @coversDefaultClass \Youwe\PHPTestingSuite\Composer\MappingResolver
 */
class MappingResolverTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::resolve
     */
    public function testResolve()
    {
        $typeResolver = $this->createMock(ProjectTypeResolver::class);
        $typeResolver
            ->expects(self::once())
            ->method('resolve')
            ->willReturn('foo');

        $mappingResolver = new MappingResolver($typeResolver);

        $this->assertInstanceOf(
            FileMappingReaderInterface::class,
            $mappingResolver->resolve()
        );
    }
}
