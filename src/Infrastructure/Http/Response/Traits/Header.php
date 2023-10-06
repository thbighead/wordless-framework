<?php

namespace Wordless\Infrastructure\Http\Response\Traits;

use Requests_Utility_CaseInsensitiveDictionary;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToFindArrayKey;

trait Header
{
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

    private function retrieveWordpressResponseHeaderObject(): ?Requests_Utility_CaseInsensitiveDictionary
    {
        return $this->raw_response['headers'] ?? null;
    }
}
