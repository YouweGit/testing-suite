<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer;

use Composer\Composer;

/**
 * Resolves the project type.
 */
class ProjectTypeResolver
{
    /**
     * The key from the composer extra section which contains other configuration.
     */
    public const COMPOSER_EXTRA_KEY = 'youwe-testing-suite';

    /**
     * The key in the configuration, which determines the overwrite for the type.
     */
    public const COMPOSER_EXTRA_TYPE_KEY = 'type';

    /** @var Composer */
    private $composer;

    /** @var array */
    private $mapping = [
        'drupal-bundle' => 'drupal',
        'drupal-project' => 'drupal',
        'magento-project' => 'magento2',
        'magento2-module' => 'magento2',
        'magento2-project' => 'magento2',
        'pimcore-bundle' => 'pimcore',
        'pimcore-project' => 'pimcore',
    ];

    public const DEFAULT_PROJECT_TYPE = 'default';

    /**
     * Constructor.
     *
     * @param Composer   $composer
     * @param array|null $mapping
     */
    public function __construct(Composer $composer, ?array $mapping = null)
    {
        $this->composer = $composer;
        $this->mapping  = $mapping ?? $this->mapping;
    }

    /**
     * Get the type.
     *
     * @return string
     */
    public function resolve(): string
    {
        $extra = $this->composer->getPackage()->getExtra();
        if (isset($extra[static::COMPOSER_EXTRA_KEY][static::COMPOSER_EXTRA_TYPE_KEY])) {
            return $extra[static::COMPOSER_EXTRA_KEY][static::COMPOSER_EXTRA_TYPE_KEY];
        }

        $packageType = $this->composer->getPackage()->getType();

        return array_key_exists($packageType, $this->mapping)
            ? $this->mapping[$packageType]
            : self::DEFAULT_PROJECT_TYPE;
    }
}
