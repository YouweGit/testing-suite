<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Command;

use Composer\Factory;
use Composer\IO\ConsoleIO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Youwe\TestingSuite\Composer\CreateLegacyFilesExceptionsList;
use Youwe\TestingSuite\Composer\Installer\LegacyFilesExceptionsInstaller;
use Youwe\TestingSuite\Composer\ProjectTypeResolver;

class LegacyFilesExceptions extends Command {

    protected static $defaultName = 'create-legacy-files-exceptions';

    protected function configure()
    {
        $this->setDescription('Creates legacy files exceptions which ' .
            'can be used to prevent having to fix all existing files after ' .
            'installing testing-suite on an running project'
        );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Creating Legacy files exceptions.');

        $composer = Factory::create(new ConsoleIO($input, $output, new HelperSet()));

        $projectTypeResolver = new ProjectTypeResolver($composer);

        $config = $composer->getConfig();
        var_dump($config->get('allow-plugins'));


        // Need to create createLegacyFilesExceptionsList here and call execute
        $createLegacyFilesExceptionList = new CreateLegacyFilesExceptionsList();
        $createLegacyFilesExceptionList->setExtensionsToScan(LegacyFilesExceptionsInstaller::LEGACY_FILES_MAPPING[$projectTypeResolver->resolve()]['extensions']);
        $createLegacyFilesExceptionList->execute(LegacyFilesExceptionsInstaller::LEGACY_FILES_MAPPING[$projectTypeResolver->resolve()]['directory']);

        $output->writeln('Files created, please check the project root.');

        return 1;
    }
}