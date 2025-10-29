<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits;

trait Loader
{
    private bool $all_meta_loaded = false;

    /** @var array<string, mixed> $meta_fields */
    private array $meta_fields = [];

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function loadMetaFields(): void
    {
        foreach ($this->callGetAllMetaData() as $meta_key => $meta_value) {
            $this->meta_fields[$meta_key] = $meta_value;
        }

        $this->all_meta_loaded = true;
    }
}
