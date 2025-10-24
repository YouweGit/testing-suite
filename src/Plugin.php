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
use UnexpectedValueException;
use Youwe\TestingSuite\Composer\Installer\InstallerInterface;

/**
 * @SuppressWarnings("PHPMD.ShortVariable")
 * @SuppressWarnings("PHPMD.UnusedFormalParameter") to allow for hooks in implemented interface methods
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    public const PACKAGE_NAME = 'youwe/testing-suite';

    /** @var InstallerInterface[] */
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
        ];
    }

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
     * @param InstallerInterface[] ...$installers
     *
     * @return void
     */
    public function addInstallers(InstallerInterface ...$installers): void
    {
        $this->installers = array_merge($this->installers, $installers);
    }

    /**
     * Run the installers when this package has been installed/updated
     *
     * @param PackageEvent $event
     * @return void
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

        $event->getIO()->write('<info>Running Youwe Testing Suite installer</info>');
        foreach ($this->installers as $installer) {
            $installer->install();
        }
    }
}
