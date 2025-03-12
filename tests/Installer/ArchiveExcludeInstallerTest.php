<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests\Installer;

use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Youwe\FileMapping\FileMappingInterface;
use Youwe\FileMapping\FileMappingReaderInterface;
use Youwe\TestingSuite\Composer\Installer\ArchiveExcludeInstaller;
use Youwe\TestingSuite\Composer\MappingResolver;

/**
 * @phpcs:disable GlobalPhpUnit.Coverage.CoversTag.CoversTagMissing
 */
#[CoversMethod(ArchiveExcludeInstaller::class, '__construct')]
#[CoversMethod(ArchiveExcludeInstaller::class, 'install')]
class ArchiveExcludeInstallerTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[DataProvider('dataProvider')]
    public function testInstall(
        array $existingFiles,
        array $files,
        array $defaults,
        array $definition,
        array $expected,
    ): void {
        $file       = $this->createMock(JsonFile::class);
        $resolver   = $this->createMock(MappingResolver::class);
        $io         = $this->createMock(IOInterface::class);
        $reader     = $this->createReaderMock($files);
        $filesystem = $this->createFilesystem($existingFiles);

        $file
            ->expects(self::once())
            ->method('read')
            ->willReturn($definition);

        $file
            ->expects(self::once())
            ->method('write')
            ->with($expected);

        $resolver
            ->expects(self::once())
            ->method('resolve')
            ->willReturn($reader);

        $installer = new ArchiveExcludeInstaller(
            $resolver,
            $io,
            $file,
            $filesystem->url(),
            $defaults,
        );

        $installer->install();
    }

    public static function dataProvider(): array
    {
        return [
            [
                [
                    'foo-file.txt',
                    'bar-file.txt',
                    'default.txt',
                ],
                [
                    'foo-file.txt',
                    'bar-file.txt',
                    'baz-file.txt',
                ],
                [
                    '/default.txt',
                    '/other-default.txt',
                ],
                [
                    'archive' => [
                        'exclude' => [
                            'existing.txt',
                        ],
                    ],
                ],
                [
                    'archive' => [
                        'exclude' => [
                            '/existing.txt',
                            '/default.txt',
                            '/foo-file.txt',
                            '/bar-file.txt',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws Exception
     */
    private function createReaderMock(array $files): FileMappingReaderInterface
    {
        $mock = $this->createMock(FileMappingReaderInterface::class);

        $valids   = array_fill(0, count($files), true);
        $valids[] = false;

        $mappings = array_map(
            function (string $file): FileMappingInterface {
                $mapping = $this->createMock(FileMappingInterface::class);
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
            ->method('key')
            ->willReturn(...array_keys($mappings));

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
            array_map('strval', array_flip($files))
        );
    }
}
