<?php

/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace Youwe\PHPTestingSuite\Composer\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Youwe\PHPTestingSuite\Composer\Factory\ProcessFactory;
use Symfony\Component\Process\Process;

/**
 * @coversDefaultClass \Youwe\PHPTestingSuite\Composer\Factory\ProcessFactory
 */
class ProcessFactoryTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::create
     */
    public function testCreate()
    {
        $factory = new ProcessFactory();

        $this->assertInstanceOf(
            Process::class,
            $factory->create('foo')
        );
    }
}
