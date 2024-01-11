<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Contracts;

use Wordless\Wordpress\Enums\ObjectType;

interface IRelatedMetaData
{
    public static function objectType(): ObjectType;

    public function getMetaField(string $meta_key, mixed $default = null): mixed;

    /**
     * @return array<string, mixed>
     */
    public function getMetaFields(): array;

    public function loadMetaFields(): void;
}
