<?php

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits;

use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Create\Traits\InternalAdders;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Create\Traits\InternalAdders\Exceptions\FailedToInsertMetaData;

trait Create
{
    use InternalAdders;

    /**
     * @param string $meta_key
     * @param string $meta_value
     * @return int
     * @throws FailedToInsertMetaData
     */
    public function createMetaField(string $meta_key, string $meta_value): int
    {
        return $this->callAddMetaData($meta_key, $meta_value);
    }

    /**
     * @param string $meta_key
     * @param string $meta_value
     * @return int
     * @throws FailedToInsertMetaData
     */
    public function createUniqueMetaField(string $meta_key, string $meta_value): int
    {
        return $this->callAddMetaData($meta_key, $meta_value, true);
    }
}
