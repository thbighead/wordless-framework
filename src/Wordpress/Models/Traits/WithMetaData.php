<?php

namespace Wordless\Wordpress\Models\Traits;

use Wordless\Exceptions\InvalidMetaKey;

trait WithMetaData
{
    /** @var array<string, mixed> $metaFields */
    private array $metaFields = [];
    /** @var bool $all_meta_loaded */
    private bool $all_meta_loaded = false;

    /**
     * @param string $meta_key
     * @param $default
     * @return mixed
     * @throws InvalidMetaKey
     */
    public function getMetaField(string $meta_key, $default = null)
    {
        if (empty($meta_key)) {
            throw new InvalidMetaKey($meta_key);
        }

        if (key_exists($meta_key, $this->metaFields)) {
            return $this->metaFields[$meta_key];
        }

        $this->metaFields[$meta_key] = $this->callGetMetaData($meta_key);

        if ($this->metaFields[$meta_key] === false) {
            return $default;
        }

        return $this->metaFields[$meta_key];
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

    public function loadMetaFields(): void
    {
        foreach ($this->callGetMetaData() as $meta_key => $meta_value) {
            $this->metaFields[$meta_key] = $meta_value;
        }

        $this->all_meta_loaded = true;
    }

    /**
     * @param string $meta_key
     * @param bool $single_result
     * @return mixed
     */
    final protected function callGetMetaData(string $meta_key = '', bool $single_result = true)
    {
        return get_metadata(static::objectType(), $this->ID, $meta_key, $single_result);
    }
}
