<?php

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Create\Traits;

use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Create\Traits\InternalAdders\Exceptions\FailedToInsertMetaData;

trait InternalAdders
{
    /**
     * @param string $meta_key
     * @param string $meta_value
     * @param bool $unique
     * @return int
     * @throws FailedToInsertMetaData
     */
    private function callAddMetaData(string $meta_key, string $meta_value, bool $unique = false): int
    {
        if (($new_meta_id = add_metadata(
                static::objectType()->name,
                $this->ID,
                $meta_key,
                $meta_value,
                $unique
            )) === false) {
            throw new FailedToInsertMetaData($this, $meta_key, $meta_value, $unique);
        }

        return $new_meta_id;
    }
}
