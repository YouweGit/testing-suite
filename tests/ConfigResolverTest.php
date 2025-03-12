<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Youwe\TestingSuite\Composer\ConfigResolver;
use Youwe\TestingSuite\Composer\ProjectTypeResolver;

/**
 * @phpcs:disable GlobalPhpUnit.Coverage.CoversTag.CoversTagMissing
 */
#[CoversMethod(ConfigResolver::class, '__construct')]
#[CoversMethod(ConfigResolver::class, 'resolve')]
class ConfigResolverTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testResolve(): void
    {
        $jsonFile   = 'default.json';
        $jsonData   = '{"sort-packages": true}';
        $filesystem = vfsStream::setup(
            sha1(__METHOD__),
            null,
            [$jsonFile => $jsonData],
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
