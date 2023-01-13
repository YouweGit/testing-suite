<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer;

use SplFileInfo;

class CreateLegacyFilesExceptionsList
{
    private array $files = [];
    private array $extensionsToScan = ['php'];

    /**
     * @param string $directory
     *
     * @return void
     */
    public function execute(string $directory): void
    {
        /* @var $file SplFileInfo */
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(__DIR__ . '/' . $directory)) as $file) {
            if ($file->isDir() || !in_array($file->getExtension(), $this->extensionsToScan)) {
                continue;
            }

            $this->files[] = str_replace(__DIR__ . '/', '', $file->getRealPath());;
        }

        $this->generatePhpCs();
        $this->generatePhpMd();
        $this->generatePhpStan();
    }

    /**
     * @param array $extensionsToScan
     *
     * @return void
     */
    public function setExtensionsToScan(array $extensionsToScan): void
    {
        $this->extensionsToScan = $extensionsToScan;
    }

    /**
     * @param $template
     * @param $lineTemplate
     * @param $fileName
     *
     * @return void
     */
    private function generateLegacyFile($template, $lineTemplate, $fileName): void
    {
        foreach ($this->files as $file) {
            $template .= sprintf($lineTemplate, $file);
        }

        file_put_contents($fileName, $template);
    }

    /**
     * @return void
     */
    private function generatePhpStan(): void
    {
        $template = 'parameters:' . PHP_EOL .
            '  exclude_analyse:' . PHP_EOL;
        $lineTemplate = '    - **/%s' . PHP_EOL;

        $this->generateLegacyFile($template, $lineTemplate, 'phpstan.neon.legacy');
    }

    /**
     * @return void
     */
    private function generatePhpMd(): void
    {
        $template = '';
        $lineTemplate = '    <exclude-pattern>%s</exclude-pattern>' . PHP_EOL;

        $this->generateLegacyFile($template, $lineTemplate, 'phpmd.xml.legacy');
    }

    /**
     * @return void
     */
    private function generatePhpCs(): void
    {
        $template = '';
        $lineTemplate = '    <exclude-pattern>%s</exclude-pattern>' . PHP_EOL;

        $this->generateLegacyFile($template, $lineTemplate, 'phpcs.xml.legacy');
    }
}
