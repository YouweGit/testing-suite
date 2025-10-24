<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer\Installer;

use Youwe\TestingSuite\Composer\ComposerJsonWriter;
use Youwe\TestingSuite\Composer\ConfigResolver;

/**
 * @SuppressWarnings("PHPMD.ShortVariable")
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
class ConfigInstaller implements InstallerInterface
{
    /**
     * Constructor.
     *
     * @param ConfigResolver $resolver
     * @param ComposerJsonWriter $composerJsonWriter
     */
    public function __construct(
        private readonly ConfigResolver $resolver,
        private readonly ComposerJsonWriter $composerJsonWriter,
    ) {
    }

    /**
     * Install.
     *
     * @return void
     */
    public function install(): void
    {
        $this->composerJsonWriter->mergeContents(
            settings: ['config' => $this->resolver->resolve()],
            overwrite: false,
        );
    }
}
