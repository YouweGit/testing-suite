<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

use Youwe\TestingSuite\Composer\ComposerJsonWriter;
use Youwe\TestingSuite\Composer\ConfigResolver;
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
$composerJsonWriter = new ComposerJsonWriter();

return [
    new FilesInstaller($mappingResolver, $io),
    new ArchiveExcludeInstaller($mappingResolver, $io, $composerJsonWriter),
    new PackagesInstaller($composer, $typeResolver, $io),
    new ConfigInstaller($configResolver, $composerJsonWriter),
];
