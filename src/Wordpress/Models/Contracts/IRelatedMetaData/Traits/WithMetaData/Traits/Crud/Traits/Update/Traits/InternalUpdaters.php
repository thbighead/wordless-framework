<?php

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Update\Traits;

use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Update\Traits\InternalUpdaters\Exceptions\FailedToUpdateMetaData;

trait InternalUpdaters
{
    /**
     * @param string $meta_key
     * @param string $meta_value
     * @param string|null $if_value_is
     * @return int
     * @throws FailedToUpdateMetaData
     */
    private function callUpdateMetaData(string $meta_key, string $meta_value, ?string $if_value_is = null): int|true
    {
        if (($result = update_metadata(
                static::objectType()->name,
                $this->ID,
                $meta_key,
                $meta_value,
                $if_value_is = empty($if_value_is) ? '' : $if_value_is
            )) === false) {
            throw new FailedToUpdateMetaData($this, $meta_key, $meta_value, $if_value_is);
        }

        return $result;
    }
}
