<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Youwe\FileMapping\FileMappingReaderInterface;
use Youwe\TestingSuite\Composer\MappingResolver;
use Youwe\TestingSuite\Composer\ProjectTypeResolver;

/**
 * @phpcs:disable GlobalPhpUnit.Coverage.CoversTag.CoversTagMissing
 */
#[CoversMethod(MappingResolver::class, '__construct')]
#[CoversMethod(MappingResolver::class, 'resolve')]
class MappingResolverTest extends TestCase
{
    /**
     * @throws Exception
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
