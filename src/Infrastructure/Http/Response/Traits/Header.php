<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Http\Response\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToFindArrayKey;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;
use WpOrg\Requests\Utility\CaseInsensitiveDictionary;

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

    public function sendHeaders(): static
    {
        if (!headers_sent()) {
            foreach ($this->getHeaders() as $header => $value) {
                header("$header: $value");
            }
        }

        return $this;
    }

    private function retrieveWordpressResponseHeaderObject(): ?CaseInsensitiveDictionary
    {
        return $this->raw_response['headers'] ?? null;
    }
}
