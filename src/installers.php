<?php

/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

use Mediact\Composer\FileInstaller;
use Mediact\FileMapping\UnixFileMappingReader;
use Youwe\PHPTestingSuite\Composer\Factory\ProcessFactory;
use Youwe\PHPTestingSuite\Composer\Installer\ArchiveExcludeInstaller;
use Youwe\PHPTestingSuite\Composer\Installer\ConfigInstaller;
use Youwe\PHPTestingSuite\Composer\Installer\FilesInstaller;
use Youwe\PHPTestingSuite\Composer\Installer\PackagesInstaller;
use Youwe\PHPTestingSuite\Composer\MappingResolver;
use Youwe\PHPTestingSuite\Composer\ProjectTypeResolver;
use Youwe\PHPTestingSuite\Composer\ConfigResolver;

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
    new ConfigInstaller($configResolver, $io)
];
