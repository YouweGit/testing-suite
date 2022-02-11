<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests;

use PHPUnit\Framework\TestCase;
use Youwe\FileMapping\FileMappingReaderInterface;
use Youwe\TestingSuite\Composer\MappingResolver;
use Youwe\TestingSuite\Composer\ProjectTypeResolver;

/**
 * @coversDefaultClass \Youwe\TestingSuite\Composer\MappingResolver
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
