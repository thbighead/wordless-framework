<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\ApiController;

use Wordless\Infrastructure\Http\MutableHeaderBag;
use WP_REST_Request;

class Request extends WP_REST_Request implements MutableHeaderBag
{
    final public const EDITABLE = 'PUT, PATCH';

    /** @var array<string, mixed> $validated_fields */
    protected array $validated_fields;

    /**
     * @param WP_REST_Request $wpRestRequest
     * @param array<string, mixed> $validated_fields
     * @return Request
     */
    public static function fromWpRestRequest(WP_REST_Request $wpRestRequest, array $validated_fields): Request
    {
        return new static(
            $validated_fields,
            $wpRestRequest->method,
            $wpRestRequest->route,
            $wpRestRequest->attributes
        );
    }

    public function __construct(
        array  $validated_fields,
        string $method = '',
        string $route = '',
        array  $attributes = []
    )
    {
        parent::__construct($method, $route, $attributes);
        $this->validated_fields = $validated_fields;
    }

    public function getHeader(string $key, string|array|null $default = null): ?string
    {
        return $this->get_header($key) ?? $default;
    }

    public static function isToRestApi(): bool
    {
        return defined('REST_REQUEST');
    }

    /**
     * @inheritdoc
     */
    public function getHeaders(): array
    {
        return $this->get_headers();
    }

    public function getValidParam(?string $param = null, mixed $default = null): mixed
    {
        if ($param === null) {
            return $this->validated_fields;
        }

        return $this->validated_fields[$param] ?? $default;
    }

    public function hasHeader(string $key): bool
    {
        return $this->getHeader($key) !== null;
    }

    public function removeHeader(string $key): static
    {
        if ($this->hasHeader($key)) {
            $this->remove_header($key);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeHeaders(array $headers): static
    {
        foreach ($headers as $header) {
            $this->removeHeader($header);
        }

        return $this;
    }

    public function setHeader(string $key, string $value, bool $override = false): static
    {
        if ($this->hasHeader($key) && !$override) {
            return $this;
        }

        $this->set_header($key, $value);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setHeaders(array $headers, bool $override = false): static
    {
        $this->set_headers($headers, $override);

        return $this;
    }
}
