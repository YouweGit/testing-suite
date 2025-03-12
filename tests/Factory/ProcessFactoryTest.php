<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests\Factory;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use Youwe\TestingSuite\Composer\Factory\ProcessFactory;

/**
 * @phpcs:disable GlobalPhpUnit.Coverage.CoversTag.CoversTagMissing
 */
#[CoversMethod(ProcessFactory::class, 'create')]
class ProcessFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $factory = new ProcessFactory();

        $this->assertInstanceOf(
            Process::class,
            $factory->create('foo'),
        );
    }
}
