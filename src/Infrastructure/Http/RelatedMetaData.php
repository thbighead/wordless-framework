<?php

namespace Wordless\Infrastructure\Http;

interface RelatedMetaData
{
    public static function objectType(): string;

    /**
     * @param string $meta_key
     * @param mixed $default
     * @return mixed
     */
    public function getMetaField(string $meta_key, $default = null);

    /**
     * @return array<string, mixed>
     */
    public function getMetaFields(): array;

    public function loadMetaFields(): void;
}
