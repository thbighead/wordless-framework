<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits;

trait Loader
{
    private bool $all_meta_loaded = false;

    /** @var array<string, mixed> $metaFields */
    private array $metaFields = [];

    private function loadMetaFields(): void
    {
        foreach ($this->callGetAllMetaData() as $meta_key => $meta_value) {
            $this->metaFields[$meta_key] = $meta_value;
        }

        $this->all_meta_loaded = true;
    }
}
