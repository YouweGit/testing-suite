<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer;

use Composer\Composer;
use Composer\DependencyResolver\Operation;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use UnexpectedValueException;
use Youwe\TestingSuite\Composer\Installer\InstallerInterface;
use Youwe\TestingSuite\Composer\Installer\PostPackageChangeInstallerInterface;

/**
 * @SuppressWarnings("PHPMD.ShortVariable")
 * @SuppressWarnings("PHPMD.UnusedFormalParameter") to allow for hooks in implemented interface methods
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    public const PACKAGE_NAME = 'youwe/testing-suite';

    private bool $isThisPackageChanged = false;

    /** @var list<InstallerInterface|PostPackageChangeInstallerInterface> */
    private array $installers;

    /**
     * Subscribe to post update and post install command.
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'post-package-install' => [ 'onPackageChange' ],
            'post-package-update' => [ 'onPackageChange' ],
            'post-install-cmd' => [ 'onPostInstall' ],
            'post-update-cmd' => [ 'onPostInstall' ],
        ];
    }

    /**
     * Constructor.
     *
     * @param InstallerInterface|PostPackageChangeInstallerInterface ...$installers
     */
    public function __construct(InstallerInterface|PostPackageChangeInstallerInterface ...$installers)
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
    public function activate(Composer $composer, IOInterface $io): void
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
    public function deactivate(Composer $composer, IOInterface $io): void
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
    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }

    /**
     * Add installers.
     *
     * @param InstallerInterface|PostPackageChangeInstallerInterface ...$installers
     *
     * @return void
     */
    public function addInstallers(InstallerInterface|PostPackageChangeInstallerInterface ...$installers): void
    {
        $this->installers = array_merge($this->installers, $installers);
    }

    /**
     * Register whether the `youwe/testing-suite` is actually changed during this composer operation and run all the
     * post-package-change installers (installers implementing PostPackageChangeInstallerInterface)
     */
    public function onPackageChange(PackageEvent $event): void
    {
        $operation = $event->getOperation();

        $packageName = match (true) {
            $operation instanceof Operation\InstallOperation => $operation->getPackage()->getName(),
            $operation instanceof Operation\UpdateOperation => $operation->getTargetPackage()->getName(),
            default => throw new UnexpectedValueException('Unexpected operation type: ' . $operation::class),
        };

        if ($packageName !== self::PACKAGE_NAME) {
            return;
        }

        $this->isThisPackageChanged = true;

        $event->getIO()->write('<info>Running Youwe Testing Suite pre-installer</info>');
        foreach ($this->installers as $installer) {
            if ($installer instanceof PostPackageChangeInstallerInterface) {
                $installer->installPostPackageChange();
            }
        }
    }

    /**
     * Run the installers when this package has been installed/updated
     */
    public function onPostInstall(Event $event): void
    {
        if (!$this->isThisPackageChanged) {
            return;
        }

        $event->getIO()->write('<info>Running Youwe Testing Suite installer</info>');
        foreach ($this->installers as $installer) {
            if ($installer instanceof InstallerInterface) {
                $installer->install();
            }
        }
    }
}
