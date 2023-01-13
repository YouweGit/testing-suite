<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Installer;

use Composer\IO\IOInterface;
use Youwe\TestingSuite\Composer\CreateLegacyFilesExceptionsList;
use Youwe\TestingSuite\Composer\MappingResolver;
use Youwe\TestingSuite\Composer\ProjectTypeResolver;

class LegacyFilesExceptionsInstaller implements InstallerInterface {

    private IOInterface $io;

    private ProjectTypeResolver $typeResolver;

    public const LEGACY_FILES_MAPPING = [
        MappingResolver::DEFAULT_MAPPING_TYPE => [],
        'magento2' => [
            'directory' => 'app/code',
            'extensions' => ['php']
        ]
    ];

    /**
     * Constructor.
     *
     * @param IOInterface         $io
     * @param ProjectTypeResolver $typeResolver
     */
    public function __construct(
        IOInterface $io,
        ProjectTypeResolver $typeResolver,
    ) {
        $this->io = $io;
        $this->typeResolver = $typeResolver;
    }

    public function install(): void
    {
        if (file_exists('legacy-files-check-done')) {
            $this->io->write('legacy-files-check-done detected. Skipping legacy files check.');
            return;
        }
        $type = $this->typeResolver->resolve();
        if (!isset(self::LEGACY_FILES_MAPPING[$type])) {
            $this->io->write('This project type is not yet supported by the legacy files checker.');
            return;
        }

        if ($this->io->askConfirmation('Do you want to create legacy file exceptions for the testing suite? (y/N)' . PHP_EOL, false)) {
            $createLegacyFilesExceptionList = new CreateLegacyFilesExceptionsList();
            $createLegacyFilesExceptionList->setExtensionsToScan(self::LEGACY_FILES_MAPPING[$type]['extensions']);
            $createLegacyFilesExceptionList->execute(self::LEGACY_FILES_MAPPING[$type]['directory']);
            file_put_contents('legacy-files-check-done', '');
            $this->io->write('Files created, please check the project root.');
        } else {
            if ($this->io->askConfirmation('Should we stop asking? (Y/n)' . PHP_EOL)) {
                file_put_contents('legacy-files-check-done', '');
            }
        }
    }
}