<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\ApiController;

use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Http\MutableHeaderBag;
use Wordless\Infrastructure\Http\Response\Enums\StatusCode;
use WP_Error;
use WP_REST_Response;

class Response extends WP_REST_Response implements MutableHeaderBag
{
    private const DATA_KEY_STATUS = 'status';

    private ?WP_Error $wpError = null;

    public static function canonicalizeHeaderName(string $key): string
    {
        return Str::slugCase($key);
    }

    public static function error(StatusCode $http_code, string $message, array $data = []): Response
    {
        return (new static)->setWpError($http_code, $message, $data);
    }

    public function getHeader(string $key, ?string $default = null): ?string
    {
        return $this->headers[self::canonicalizeHeaderName($key)] ?? $default;
    }

    /**
     * @inheritdoc
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $key): bool
    {
        return $this->getHeader($key) !== null;
    }

    public function removeHeader(string $key): Response
    {
        if ($this->hasHeader($key)) {
            unset($this->headers[self::canonicalizeHeaderName($key)]);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeHeaders(array $headers): Response
    {
        foreach ($headers as $header) {
            $this->removeHeader($header);
        }

        return $this;
    }

    public function respond(): WP_Error|static
    {
        return $this->wpError ?? $this;
    }

    public function setHeader(string $key, string $value, bool $override = false): Response
    {
        $this->header(self::canonicalizeHeaderName($key), $value, $override);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setHeaders(array $headers, bool $override = false): Response
    {
        foreach ($headers as $header_key => $header_value) {
            $this->setHeader($header_key, $header_value, $override);
        }

        return $this;
    }

    public function setWpError(StatusCode $http_code, string $message, array $data = []): Response
    {
        if (isset($data[self::DATA_KEY_STATUS])) {
            $data['resource_' . self::DATA_KEY_STATUS] = $data[self::DATA_KEY_STATUS];
            unset($data[self::DATA_KEY_STATUS]);
        }

        $this->wpError = new WP_Error(
            $http_code->asText(),
            $message,
            [self::DATA_KEY_STATUS => $http_code->value] + $data
        );

        return $this;
    }
}
