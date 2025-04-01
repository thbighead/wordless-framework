<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
declare(strict_types=1);

namespace Wordless\Infrastructure\Http;

use JsonException;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToFindArrayKey;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;
use Wordless\Application\Helpers\Http;
use Wordless\Infrastructure\Http\Response\Exceptions\FailedToPrepareResponseBodyToSend;
use Wordless\Infrastructure\Http\Response\Traits\Cookies;
use Wordless\Infrastructure\Http\Response\Traits\Header;
use Wordless\Infrastructure\Http\Response\Traits\StatusCode;

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

    /**
     * @param string|null $custom_body
     * @return void
     * @throws FailedToParseArrayKey
     * @throws FailedToPrepareResponseBodyToSend
     * @throws JsonException
     */
    public function respond(?string $custom_body = null): void
    {
        $this->sendStatusCode()
            ->sendHeaders()
            ->sendBody($custom_body);

        exit;
    }

    /**
     * @param string|null $custom_body
     * @return $this
     * @throws FailedToParseArrayKey
     * @throws FailedToPrepareResponseBodyToSend
     * @throws JsonException
     */
    public function sendBody(?string $custom_body = null): static
    {
        $body = $custom_body ?? $this->body();

        if (is_array($body)) {
            $body = json_encode($body, JSON_THROW_ON_ERROR);
        }

        if (!is_string($body)) {
            throw new FailedToPrepareResponseBodyToSend($body);
        }

        echo $body;

        return $this;
    }
}
