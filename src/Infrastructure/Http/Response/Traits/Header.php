<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Http\Response\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToFindArrayKey;
use WpOrg\Requests\Utility\CaseInsensitiveDictionary;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;

trait Header
{
    /**
     * @param string $key
     * @param array|string|null $default
     * @return string|array|null
     * @throws FailedToParseArrayKey
     */
    public function getHeader(string $key, array|string|null $default = null): string|array|null
    {
        try {
            return Arr::getOrFail($this->getHeaders(), $key);
        } catch (FailedToFindArrayKey) {
            return $default;
        }
    }

    public function getHeaders(): array
    {
        return $this->retrieveWordpressResponseHeaderObject()?->getAll() ?? [];
    }

    public function hasHeader(string $key): bool
    {
        return isset($this->getHeaders()[$key]);
    }

    private function retrieveWordpressResponseHeaderObject(): ?CaseInsensitiveDictionary
    {
        return $this->raw_response['headers'] ?? null;
    }
}
