<?php

declare(strict_types=1);

namespace Youwe\TestingSuite\Composer;

use Composer\Factory;
use UnexpectedValueException;

class ComposerJsonWriter
{
    private readonly string $file;

    public function __construct(?string $file = null)
    {
        $this->file = $file ?? Factory::getComposerFile();
    }

    public function getContents(): object
    {
        return json_decode(file_get_contents($this->file), associative: false, flags: JSON_THROW_ON_ERROR);
    }

    public function setContents(object $contents): void
    {
        file_put_contents(
            $this->file,
            json_encode($contents, flags: JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        );
    }

    public function mergeContents(object|array $settings, bool $overwrite = false): void
    {
        $this->setContents(
            $this->mergeObject($this->getContents(), $settings, $overwrite),
        );
    }

    private function mergeObject(?object $existing, object|array $new, bool $overwrite): object
    {
        if ($existing === null) {
            $existing = (object) [];
        }

        foreach ((array) $new as $key => $value) {
            if (is_array($value) && array_is_list($value)) {
                // Merge lists
                $existing->{$key} = $this->mergeList($existing->{$key} ?? [], $value);
                continue;
            }

            if (is_object($value) || is_array($value)) {
                // Deep merge new config
                $existing->{$key} = $this->mergeObject($existing->{$key} ?? null, $value, $overwrite);
                continue;
            }

            if (!$overwrite && isset($existing->{$key})) {
                continue;
            }

            $existing->{$key} = $value;
        }

        return $existing;
    }

    private function mergeList(mixed $existing, array $new): array
    {
        if ($existing !== null && !is_array($existing)) {
            throw new UnexpectedValueException('Can\'t merge an array list with ' . get_debug_type($existing));
        }

        return array_merge($existing ?? [], $new);
    }
}
