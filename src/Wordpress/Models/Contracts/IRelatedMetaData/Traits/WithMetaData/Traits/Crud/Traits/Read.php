<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits;

use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Read\Exceptions\InvalidMetaKey;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Read\Traits\InternalGetters;

trait Read
{
    use InternalGetters;

    /**
     * @param string $meta_key
     * @param mixed|null $default
     * @return mixed
     * @throws InvalidMetaKey
     */
    public function getMetaField(string $meta_key, mixed $default = null): mixed
    {
        if (empty($meta_key)) {
            throw new InvalidMetaKey($meta_key);
        }

        if (isset($this->metaFields[$meta_key])) {
            return $this->metaFields[$meta_key];
        }

        if (is_null($meta_field_value = $this->callGetFirstMetaData($meta_key))) {
            return $default;
        }

        return $this->metaFields[$meta_key] = $meta_field_value;
    }

    /**
     * @return array<string, mixed>
     */
    public function getMetaFields(): array
    {
        if (!$this->all_meta_loaded) {
            $this->loadMetaFields();
        }

        return $this->metaFields;
    }
}
