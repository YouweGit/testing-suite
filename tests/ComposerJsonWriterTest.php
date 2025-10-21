<?php

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Tests;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Youwe\TestingSuite\Composer\ComposerJsonWriter;

#[CoversMethod(ComposerJsonWriter::class, '__construct')]
#[CoversMethod(ComposerJsonWriter::class, 'getContents')]
#[CoversMethod(ComposerJsonWriter::class, 'setContents')]
#[CoversMethod(ComposerJsonWriter::class, 'mergeContents')]
class ComposerJsonWriterTest extends TestCase
{
    private function getFilesystem(string $composerJson = '{}'): string
    {
        static $callNr = 0;

        $filesystem = vfsStream::setup(
            sha1(__METHOD__ . ($callNr++)),
            null,
            ['composer.json' => $composerJson],
        );

        return $filesystem->url() . '/composer.json';
    }

    public function testGetContents(): void
    {
        $file = $this->getFilesystem(
            <<<'EOF'
                {
                    "name": "youwe/testing-suite",
                    "empty-object": {},
                    "empty-array": [],
                    "nested-object": {"foo": "bar"},
                    "nested-array": [42]
                }
                EOF,
        );

        $composerJsonWriter = new ComposerJsonWriter($file);

        $this->assertEquals(
            (object) [
                'name' => 'youwe/testing-suite',
                'empty-object' => (object) [],
                'empty-array' => [],
                'nested-object' => (object) ['foo' => 'bar'],
                'nested-array' => [42],
            ],
            $composerJsonWriter->getContents(),
        );
    }

    public function testSetContents(): void
    {
        $file = $this->getFilesystem();

        $composerJsonWriter = new ComposerJsonWriter($file);
        $composerJsonWriter->setContents(
            (object) [
                'name' => 'youwe/testing-suite',
                'empty-object' => (object) [],
                'empty-array' => [],
                'nested-object' => (object) ['foo' => 'bar'],
                'nested-array' => [42],
            ],
        );

        $this->assertEquals(
            <<<'EOF'
                {
                    "name": "youwe/testing-suite",
                    "empty-object": {},
                    "empty-array": [],
                    "nested-object": {
                        "foo": "bar"
                    },
                    "nested-array": [
                        42
                    ]
                }
                EOF,
            file_get_contents($file),
        );
    }

    #[DataProvider('mergeContentsDataProvider')]
    public function testMergeContents(string $existing, array|object $settings, bool $overwrite, string $expected): void
    {
        $file = $this->getFilesystem($existing);

        $composerJsonWriter = new ComposerJsonWriter($file);
        $composerJsonWriter->mergeContents($settings, $overwrite);

        $this->assertEquals(
            $expected,
            file_get_contents($file),
        );
    }

    /**
     * @return array<string, array{
     *     existing: string,
     *     settinsg: array|object,
     *     overwrite: bool,
     *     expected: string,
     * }>
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public static function mergeContentsDataProvider(): array
    {
        return [
            'It appends settings when passed as object (overwrite true)' => [
                'existing' => <<<EOF
                                  {
                                      "name": "youwe/testing-suite",
                                      "config": {
                                          "hello": "world"
                                      }
                                  }
                                  EOF,
                'settings' => (object) ['config' => (object) ['foo' => 'bar']],
                'overwrite' => true,
                'expected' => <<<EOF
                                  {
                                      "name": "youwe/testing-suite",
                                      "config": {
                                          "hello": "world",
                                          "foo": "bar"
                                      }
                                  }
                                  EOF,
            ],
            'It appends settings when passed as object (overwrite false)' => [
                'existing' => <<<EOF
                                  {
                                      "name": "youwe/testing-suite",
                                      "config": {
                                          "hello": "world"
                                      }
                                  }
                                  EOF,
                'settings' => (object) ['config' => (object) ['foo' => 'bar']],
                'overwrite' => false,
                'expected' => <<<EOF
                                  {
                                      "name": "youwe/testing-suite",
                                      "config": {
                                          "hello": "world",
                                          "foo": "bar"
                                      }
                                  }
                                  EOF,
            ],
            'It appends settings when passed as associative array (overwrite true)' => [
                'existing' => <<<EOF
                                  {
                                      "name": "youwe/testing-suite",
                                      "config": {
                                          "hello": "world"
                                      }
                                  }
                                  EOF,
                'settings' => ['config' => ['foo' => 'bar']],
                'overwrite' => true,
                'expected' => <<<EOF
                                  {
                                      "name": "youwe/testing-suite",
                                      "config": {
                                          "hello": "world",
                                          "foo": "bar"
                                      }
                                  }
                                  EOF,
            ],
            'It appends settings when passed as associative array (overwrite false)' => [
                'existing' => <<<EOF
                                  {
                                      "name": "youwe/testing-suite",
                                      "config": {
                                          "hello": "world"
                                      }
                                  }
                                  EOF,
                'settings' => ['config' => ['foo' => 'bar']],
                'overwrite' => false,
                'expected' => <<<EOF
                                  {
                                      "name": "youwe/testing-suite",
                                      "config": {
                                          "hello": "world",
                                          "foo": "bar"
                                      }
                                  }
                                  EOF,
            ],
            'It overwrites settings when told so' => [
                'existing' => <<<EOF
                                  {
                                      "name": "youwe/testing-suite",
                                      "config": {
                                          "hello": "world"
                                      }
                                  }
                                  EOF,
                'settings' => ['config' => ['hello' => 'Youwe', 'foo' => 'bar']],
                'overwrite' => true,
                'expected' => <<<EOF
                                  {
                                      "name": "youwe/testing-suite",
                                      "config": {
                                          "hello": "Youwe",
                                          "foo": "bar"
                                      }
                                  }
                                  EOF,
            ],
            'It keeps settings when not overwriting' => [
                'existing' => <<<EOF
                                  {
                                      "name": "youwe/testing-suite",
                                      "config": {
                                          "hello": "world"
                                      }
                                  }
                                  EOF,
                'settings' => ['config' => ['hello' => 'Youwe', 'foo' => 'bar']],
                'overwrite' => false,
                'expected' => <<<EOF
                                  {
                                      "name": "youwe/testing-suite",
                                      "config": {
                                          "hello": "world",
                                          "foo": "bar"
                                      }
                                  }
                                  EOF,
            ],
            'It merges arrays' => [
                'existing' => <<<EOF
                                  {
                                      "name": "youwe/testing-suite",
                                      "repositories": [
                                          {
                                              "type": "composer",
                                              "url": "https://packagist.org/"
                                          }
                                      ]
                                  }
                                  EOF,
                'settings' => [
                    'repositories' => [
                        ['type' => 'vcs', 'url' => 'https://github.com/YouweGit/testing-suite.git'],
                    ],
                ],
                'overwrite' => false,
                'expected' => <<<EOF
                    {
                        "name": "youwe/testing-suite",
                        "repositories": [
                            {
                                "type": "composer",
                                "url": "https://packagist.org/"
                            },
                            {
                                "type": "vcs",
                                "url": "https://github.com/YouweGit/testing-suite.git"
                            }
                        ]
                    }
                    EOF,
            ],
        ];
    }
}
