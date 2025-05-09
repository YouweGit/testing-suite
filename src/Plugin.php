<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer;

use Override;
use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Youwe\TestingSuite\Composer\Installer\InstallerInterface;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    /** @var InstallerInterface[] */
    private array $installers;

    /**
     * Constructor.
     *
     * @param InstallerInterface[] ...$installers
     */
    public function __construct(InstallerInterface ...$installers)
    {
        $this->installers = $installers;
    }

    /**
     * Apply plugin modifications to Composer.
     *
     *
     */
    #[Override]
    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->addInstallers(
            ...include __DIR__ . '/installers.php'
        );
    }

    /**
     * Remove any hooks from Composer.
     *
     *
     * @return void
     */
    #[Override]
    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * Prepare the plugin to be uninstalled
     *
     *
     * @return void
     */
    #[Override]
    public function uninstall(Composer $composer, IOInterface $io)
    {
    }

    /**
     * Add installers.
     *
     * @param InstallerInterface[] ...$installers
     */
    public function addInstallers(InstallerInterface ...$installers): void
    {
        $this->installers = array_merge($this->installers, $installers);
    }

    /**
     * Run the installers.
     */
    public function install(): void
    {
        foreach ($this->installers as $installer) {
            $installer->install();
        }
    }

    /**
     * Subscribe to post update and post install command.
     */
    #[Override]
    public static function getSubscribedEvents(): array
    {
        return [
            'post-install-cmd' => [
                'install'
            ],
            'post-update-cmd' => [
                'install'
            ]
        ];
    }
}
