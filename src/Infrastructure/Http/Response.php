<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Http;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToFindArrayKey;
use Wordless\Application\Helpers\Http;
use Wordless\Infrastructure\Http\Response\Traits\Cookies;
use Wordless\Infrastructure\Http\Response\Traits\Header;
use Wordless\Infrastructure\Http\Response\Traits\StatusCode;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;

class Response implements ImmutableHeaderBag
{
    use Cookies;
    use Header;
    use StatusCode;

    public function __construct(public readonly array $raw_response)
    {
    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed
     * @throws FailedToParseArrayKey
     */
    public function body(?string $key = null, mixed $default = null): mixed
    {
        $body = $this->raw_response[Http::BODY] ?? [];

        if ($key === null) {
            return $body;
        }

        try {
            return Arr::getOrFail($body, $key);
        } catch (FailedToFindArrayKey) {
            return $default;
        }
    }
}
