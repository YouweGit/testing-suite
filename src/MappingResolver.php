<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer;

use Youwe\FileMapping\FileMappingReaderInterface;
use Youwe\FileMapping\UnixFileMappingReader;

class MappingResolver
{
    /** @var ProjectTypeResolver */
    private $typeResolver;

    public const DEFAULT_MAPPING_TYPE = 'default';

    /**
     * Constructor.
     *
     * @param ProjectTypeResolver $typeResolver
     */
    public function __construct(ProjectTypeResolver $typeResolver)
    {
        $this->typeResolver = $typeResolver;
    }

    /**
     * Resolve mapping files.
     *
     * @return FileMappingReaderInterface
     */
    public function resolve(): FileMappingReaderInterface
    {
        $files = [
            __DIR__ . '/../templates/mapping/files',
            sprintf(
                __DIR__ . '/../templates/mapping/project/%s',
                $this->typeResolver->resolve()
            )
        ];

        return new UnixFileMappingReader(
            __DIR__ . '/../templates/files',
            getcwd(),
            ...$files
        );
    }

    public function getTypeResolver(): ProjectTypeResolver
    {
        return $this->typeResolver;
    }
}
