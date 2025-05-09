<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use Youwe\TestingSuite\Composer\Factory\ProcessFactory;

/**
 * @coversDefaultClass \Youwe\TestingSuite\Composer\Factory\ProcessFactory
 */
class ProcessFactoryTest extends TestCase
{
    /**
     * @covers ::create
     */
    public function testCreate(): void
    {
        $factory = new ProcessFactory();

        $this->assertInstanceOf(
            Process::class,
            $factory->create('foo')
        );
    }
}
