<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits;

use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Delete\Exceptions\FailedToDeleteMetaData;

trait Delete
{
    /**
     * @param string $meta_key
     * @param mixed|null $meta_value
     * @return void
     * @throws FailedToDeleteMetaData
     */
    public function deleteMetaField(string $meta_key, mixed $meta_value = null): void
    {
        if (delete_metadata(
                static::objectType()->name,
                $this->ID,
                $meta_key,
                empty($meta_value) ? '' : $meta_value
            ) === false) {
            throw new FailedToDeleteMetaData($this, $meta_key, $meta_value);
        }

        unset($this->metaFields[$meta_key]);
    }
}
