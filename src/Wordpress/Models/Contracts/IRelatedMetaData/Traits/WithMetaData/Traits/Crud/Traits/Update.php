<?php

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits;

use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Update\Traits\InternalUpdaters;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Update\Traits\InternalUpdaters\Exceptions\FailedToUpdateMetaData;

trait Update
{
    use InternalUpdaters;

    /**
     * @param string $meta_key
     * @param string $meta_value
     * @return int|true
     * @throws FailedToUpdateMetaData
     */
    public function updateOrCreateMetaField(string $meta_key, string $meta_value): int|true
    {
        return $this->callUpdateMetaData($meta_key, $meta_value);
    }

    /**
     * @param string $meta_key
     * @param string $meta_value
     * @param string $actual_value
     * @return int|true
     * @throws FailedToUpdateMetaData
     */
    public function updateOrCreateMetaFieldIfValueIs(
        string $meta_key,
        string $meta_value,
        string $actual_value
    ): int|true
    {
        return $this->callUpdateMetaData($meta_key, $meta_value, $actual_value);
    }
}
