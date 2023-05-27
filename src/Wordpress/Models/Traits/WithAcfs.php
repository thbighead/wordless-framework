<?php

namespace Wordless\Wordpress\Models\Traits;

trait WithAcfs
{
    private array $acfs = [];

    public function getAcf(string $field_key, $default = null)
    {
        $field_value = $this->acfs[$field_key] ?? null;

        if ($field_value === false) {
            return $default;
        }

        return $field_value ?? $default;
    }

    public function getAcfs(): array
    {
        return $this->acfs;
    }

    /**
     * @param int|string $from_id
     * @return void
     */
    private function loadAcfs(int|string $from_id): void
    {
        if (!function_exists('get_fields')) {
            return;
        }

        /** @noinspection PhpUndefinedFunctionInspection */
        if (($acfs = get_fields($from_id)) !== false) {
            $this->acfs = $acfs;
        }
    }
}
