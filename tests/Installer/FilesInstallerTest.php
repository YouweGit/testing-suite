<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests\Installer;

use Composer\IO\IOInterface;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Youwe\Composer\FileInstaller;
use Youwe\FileMapping\FileMappingInterface;
use Youwe\FileMapping\FileMappingReaderInterface;
use Youwe\TestingSuite\Composer\Installer\FilesInstaller;
use Youwe\TestingSuite\Composer\MappingResolver;

/**
 * @coversDefaultClass \Youwe\TestingSuite\Composer\Installer\FilesInstaller
 * @SuppressWarnings(PHPMD)
 */
class FilesInstallerTest extends TestCase
{
    /**
     * @param array $existingFiles
     * @param array $files
     * @param int   $expectedInstalls
     *
     * @return void
     * @dataProvider dataProvider
     *
     * @covers ::__construct
     * @covers ::install
     */
    public function testInstall(
        array $existingFiles,
        array $files,
        int $expectedInstalls
    ) {
        $filesystem    = $this->createFilesystem($existingFiles);
        $reader        = $this->createReaderMock($files, $filesystem->url());
        $resolver      = $this->createMock(MappingResolver::class);
        $io            = $this->createMock(IOInterface::class);
        $fileInstaller = $this->createMock(FileInstaller::class);

        $resolver
            ->expects(self::once())
            ->method('resolve')
            ->willReturn($reader);

        $fileInstaller
            ->expects(self::exactly($expectedInstalls))
            ->method('installFile');

        $installer = new FilesInstaller($resolver, $fileInstaller, $io);
        $installer->install();
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                [
                    'foo-file.txt',
                ],
                [
                    'foo-file.txt',
                    'bar-file.txt',
                    'baz-file.txt'
                ],
                2
            ]
        ];
    }

    /**
     * @param array  $files
     * @param string $destination
     *
     * @return FileMappingReaderInterface
     */
    private function createReaderMock(array $files, string $destination): FileMappingReaderInterface
    {
        /** @var FileMappingReaderInterface|MockObject $mock */
        $mock = $this->createMock(FileMappingReaderInterface::class);

        $valids   = array_fill(0, count($files), true);
        $valids[] = false;

        $mappings = array_map(
            function (string $file) use ($destination): FileMappingInterface {
                /** @var FileMappingInterface|MockObject $mapping */
                $mapping = $this->createMock(FileMappingInterface::class);
                $mapping
                    ->expects(self::any())
                    ->method('getDestination')
                    ->willReturn($destination . '/' . $file);

                $mapping
                    ->expects(self::any())
                    ->method('getRelativeDestination')
                    ->willReturn($file);

                return $mapping;
            },
            $files
        );

        $mock
            ->expects(self::any())
            ->method('valid')
            ->willReturn(...$valids);

        $mock
            ->expects(self::any())
            ->method('current')
            ->willReturn(...$mappings);

        return $mock;
    }

    /**
     * @param array $files
     *
     * @return vfsStreamDirectory
     */
    private function createFilesystem(array $files): vfsStreamDirectory
    {
        return vfsStream::setup(
            sha1(__METHOD__),
            null,
            array_map('strval', array_flip($files))
        );
    }
}
