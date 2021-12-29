<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

use Youwe\Composer\FileInstaller;
use Youwe\FileMapping\UnixFileMappingReader;
use Youwe\TestingSuite\Composer\ConfigResolver;
use Youwe\TestingSuite\Composer\Factory\ProcessFactory;
use Youwe\TestingSuite\Composer\Installer\ArchiveExcludeInstaller;
use Youwe\TestingSuite\Composer\Installer\ConfigInstaller;
use Youwe\TestingSuite\Composer\Installer\FilesInstaller;
use Youwe\TestingSuite\Composer\Installer\PackagesInstaller;
use Youwe\TestingSuite\Composer\MappingResolver;
use Youwe\TestingSuite\Composer\ProjectTypeResolver;

/**
 * @var Composer\Composer       $composer
 * @var Composer\IO\IOInterface $io
 */

$typeResolver    = new ProjectTypeResolver($composer);
$mappingResolver = new MappingResolver($typeResolver);
$configResolver  = new ConfigResolver($typeResolver);
$fileInstaller   = new FileInstaller(
    new UnixFileMappingReader('', '')
);
$processFactory  = new ProcessFactory();

return [
    new FilesInstaller($mappingResolver, $fileInstaller, $io),
    new ArchiveExcludeInstaller($mappingResolver, $io),
    new PackagesInstaller($composer, $typeResolver, $io),
    new ConfigInstaller($configResolver)
];
