<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits;

use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Update\Traits\InternalUpdaters;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Update\Traits\InternalUpdaters\Exceptions\FailedToUpdateMetaData;

trait Update
{
    use InternalUpdaters;

    /**
     * @param string $meta_key
     * @param mixed $meta_value
     * @return int|true
     * @throws FailedToUpdateMetaData
     */
    public function updateOrCreateMetaField(string $meta_key, mixed $meta_value): int|true
    {
        return $this->callUpdateMetaData($meta_key, $meta_value);
    }

    /**
     * @param string $meta_key
     * @param mixed $meta_value
     * @param mixed $actual_value
     * @return int|true
     * @throws FailedToUpdateMetaData
     */
    public function updateOrCreateMetaFieldIfValueIs(
        string $meta_key,
        mixed $meta_value,
        mixed $actual_value
    ): int|true
    {
        return $this->callUpdateMetaData($meta_key, $meta_value, $actual_value);
    }
}
