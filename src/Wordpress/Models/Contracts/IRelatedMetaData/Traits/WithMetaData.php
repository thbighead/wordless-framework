<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits;

use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Exceptions\InvalidMetaKey;

trait WithMetaData
{
    /** @var array<string, mixed> $metaFields */
    private array $metaFields = [];
    /** @var bool $all_meta_loaded */
    private bool $all_meta_loaded = false;

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

    final protected function callGetMetaData(string $meta_key = '', bool $single_result = true): mixed
    {
        return get_metadata(static::objectType()->name, $this->ID, $meta_key, $single_result);
    }
}
