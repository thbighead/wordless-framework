<?php

namespace Wordless\Wordpress\Models\Contracts;

use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Enums\MetableObjectType;

interface IRelatedMetaData
{
    public static function objectType(): MetableObjectType;

    public function getMetaField(string $meta_key, mixed $default = null): mixed;

    /**
     * @return array<string, mixed>
     */
    public function getMetaFields(): array;

    public function loadMetaFields(): void;
}
