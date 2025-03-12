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
        int $expectedInstalls,
    ): void {
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
                2
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
