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
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Youwe\Composer\FileInstaller;
use Youwe\FileMapping\FileMappingInterface;
use Youwe\FileMapping\FileMappingReaderInterface;
use Youwe\TestingSuite\Composer\Installer\FilesInstaller;
use Youwe\TestingSuite\Composer\MappingResolver;

/**
 * @phpcs:disable GlobalPhpUnit.Coverage.CoversTag.CoversTagMissing
 */
#[CoversMethod(FileInstaller::class, '__construct')]
#[CoversMethod(FileInstaller::class, 'install')]
class FilesInstallerTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[DataProvider('dataProvider')]
    public function testInstall(
        array $existingFiles,
        array $files,
    ): void {
        $filesystem = $this->createFilesystem($existingFiles);
        $readerMock = $this->createReaderMock($files, $filesystem->url());
        $resolverMock = $this->createMock(MappingResolver::class);
        $ioMock = $this->createMock(IOInterface::class);
        $fileInstallerMock = $this->createMock(FileInstaller::class);

        /** @var FilesInstaller&MockObject $installer */
        $installer = $this->getMockBuilder(FilesInstaller::class)
            ->setConstructorArgs([$resolverMock, $ioMock, $fileInstallerMock])
            ->onlyMethods(['getComposerFileInstaller'])
            ->getMock();

        $resolverMock
            ->expects(self::once())
            ->method('resolve')
            ->willReturn($readerMock);

        $installer->expects($this->once())
            ->method('getComposerFileInstaller')
            ->with($readerMock)
            ->willReturn($fileInstallerMock);

        $fileInstallerMock
            ->expects($this->once())
            ->method('install')
            ->with($ioMock);

        $installer->installPostPackageChange();
    }

    public static function dataProvider(): array
    {
        return [
            [
                [
                    'foo-file.txt',
                ],
                [
                    'foo-file.txt',
                    'bar-file.txt',
                    'baz-file.txt',
                ],
            ],
        ];
    }

    /**
     * @throws Exception
     */
    private function createReaderMock(array $files, string $destination): FileMappingReaderInterface
    {
        $mock = $this->createMock(FileMappingReaderInterface::class);

        $valids   = array_fill(0, count($files), true);
        $valids[] = false;

        $mappings = array_map(
            function (string $file) use ($destination): FileMappingInterface {
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

    private function createFilesystem(array $files): vfsStreamDirectory
    {
        return vfsStream::setup(
            sha1(__METHOD__),
            null,
            array_map('strval', array_flip($files)),
        );
    }
}
