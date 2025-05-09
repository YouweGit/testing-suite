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
    public const DEFAULT_MAPPING_TYPE = 'default';

    /**
     * Constructor.
     */
    public function __construct(private readonly ProjectTypeResolver $typeResolver)
    {
    }

    /**
     * Resolve mapping files.
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
