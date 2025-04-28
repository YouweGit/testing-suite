<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Youwe\TestingSuite\Composer\Installer\InstallerInterface;

/**
 * @SuppressWarnings("PHPMD.ShortVariable")
 * @SuppressWarnings("PHPMD.UnusedFormalParameter") to allow for hooks in implemented interface methods
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    /** @var InstallerInterface[] */
    private $installers;

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
     * @param Composer    $composer
     * @param IOInterface $io
     *
     * @return void
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->addInstallers(
            ...include __DIR__ . '/installers.php',
        );
    }

    /**
     * Remove any hooks from Composer.
     *
     * @param Composer    $composer
     * @param IOInterface $io
     *
     * @return void
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * Prepare the plugin to be uninstalled
     *
     * @param Composer    $composer
     * @param IOInterface $io
     *
     * @return void
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
    }

    /**
     * Add installers.
     *
     * @param InstallerInterface[] ...$installers
     *
     * @return void
     */
    public function addInstallers(InstallerInterface ...$installers)
    {
        $this->installers = array_merge($this->installers, $installers);
    }

    /**
     * Run the installers.
     *
     * @return void
     */
    public function install()
    {
        foreach ($this->installers as $installer) {
            $installer->install();
        }
    }

    /**
     * Subscribe to post update and post install command.
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'post-install-cmd' => [
                'install',
            ],
            'post-update-cmd' => [
                'install',
            ],
        ];
    }
}
