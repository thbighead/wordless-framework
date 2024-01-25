<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits;

use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Exceptions\InvalidMetaKey;

trait WithMetaData
{
    /** @var array<string, mixed> $metaFields */
    private array $metaFields = [];
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

        return $this->metaFields[$meta_key] ?? $this->metaFields[$meta_key] = $this->callGetFirstMetaData($meta_key);
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

    private function callGetAllMetaData(): array
    {
        return $this->callGetMetaData();
    }

    private function callGetFirstMetaData(string $meta_key): mixed
    {
        return $this->callGetMetaData($meta_key);
    }

    private function callGetMetaData(string $meta_key = ''): mixed
    {
        $meta_data = get_metadata(static::objectType()->name, $this->ID, $meta_key, true);

        return $meta_data === false ? null : $meta_data;
    }

    private function loadMetaFields(): void
    {
        foreach ($this->callGetAllMetaData() as $meta_key => $meta_value) {
            $this->metaFields[$meta_key] = $meta_value;
        }

        $this->all_meta_loaded = true;
    }
}
