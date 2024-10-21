<?php

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Read\Traits;

trait InternalGetters
{
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
}
