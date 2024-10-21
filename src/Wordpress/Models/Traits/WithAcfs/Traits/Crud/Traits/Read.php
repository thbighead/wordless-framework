<?php

namespace Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Crud\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;

trait Read
{
    /**
     * @param string $field_key
     * @param mixed|null $default
     * @return mixed
     * @throws FailedToParseArrayKey
     */
    public function getAcf(string $field_key, mixed $default = null): mixed
    {
        return Arr::get($this->acfs, $field_key, $default);
    }

    public function getAcfs(): array
    {
        return $this->acfs;
    }
}
