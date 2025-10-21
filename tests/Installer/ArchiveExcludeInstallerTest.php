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
use Youwe\FileMapping\FileMappingInterface;
use Youwe\FileMapping\FileMappingReaderInterface;
use Youwe\TestingSuite\Composer\ComposerJsonWriter;
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
        object $definition,
        object $expected,
    ): void {
        $composerJsonWriter = $this->createMock(ComposerJsonWriter::class);
        $resolverMock = $this->createMock(MappingResolver::class);
        $ioMock = $this->createMock(IOInterface::class);
        $readerMock = $this->createReaderMock($files);
        $filesystem = $this->createFilesystem($existingFiles);

        $composerJsonWriter
            ->expects(self::once())
            ->method('getContents')
            ->willReturn($definition);

        $composerJsonWriter
            ->expects(self::once())
            ->method('setContents')
            ->with($expected);

        $resolverMock
            ->expects(self::once())
            ->method('resolve')
            ->willReturn($readerMock);

        $installer = new ArchiveExcludeInstaller(
            $resolverMock,
            $ioMock,
            $composerJsonWriter,
            $filesystem->url(),
            $defaults,
        );

        $installer->install();
    }

    public static function dataProvider(): array
    {
        return [
            'It merges files and defaults and only adds existing files' => [
                'existingFiles' => [
                    'foo-file.txt',
                    'bar-file.txt',
                    'default.txt',
                ],
                'files' => [
                    'foo-file.txt',
                    'bar-file.txt',
                    'baz-file.txt',
                ],
                'defaults' => [
                    '/default.txt',
                    '/other-default.txt',
                ],
                'definition' => (object) [
                    'name' => 'youwe/testing-suite',
                    'archive' => (object) [
                        'exclude' => [
                            'existing.txt',
                        ],
                    ],
                ],
                'expected' => (object) [
                    'name' => 'youwe/testing-suite',
                    'archive' => (object) [
                        'exclude' => [
                            '/existing.txt',
                            '/default.txt',
                            '/foo-file.txt',
                            '/bar-file.txt',
                        ],
                    ],
                ],
            ],
            'It works when the composer.json doesn\'t have an exclude section yet' => [
                'existingFiles' => [
                    'foo-file.txt',
                ],
                'files' => [
                    'foo-file.txt',
                ],
                'defaults' => [],
                'definition' => (object) [
                    'name' => 'youwe/testing-suite',
                ],
                'expected' => (object) [
                    'name' => 'youwe/testing-suite',
                    'archive' => (object) [
                        'exclude' => [
                            '/foo-file.txt',
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

        $valids = array_fill(0, count($files), true);
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
            $files,
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
            array_map('strval', array_flip($files)),
        );
    }
}
