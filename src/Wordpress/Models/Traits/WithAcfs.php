<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;

trait WithAcfs
{
    private array $acfs = [];

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
