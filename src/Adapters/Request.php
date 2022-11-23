<?php

namespace Wordless\Adapters;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Wordless\Contracts\Adapter\HeaderBag;
use WP_REST_Request;

class Request extends WP_REST_Request implements HeaderBag
{
    public const EDITABLE = 'PUT, PATCH';
    public const HTTP_CONNECT = SymfonyRequest::METHOD_CONNECT;
    public const HTTP_DELETE = SymfonyRequest::METHOD_DELETE;
    public const HTTP_GET = SymfonyRequest::METHOD_GET;
    public const HTTP_HEAD = SymfonyRequest::METHOD_HEAD;
    public const HTTP_OPTIONS = SymfonyRequest::METHOD_OPTIONS;
    public const HTTP_PATCH = SymfonyRequest::METHOD_PATCH;
    public const HTTP_POST = SymfonyRequest::METHOD_POST;
    public const HTTP_PUT = SymfonyRequest::METHOD_PUT;
    public const HTTP_PURGE = SymfonyRequest::METHOD_PURGE;
    public const HTTP_TRACE = SymfonyRequest::METHOD_TRACE;

    /**
     * @param WP_REST_Request $wpRestRequest
     * @return static
     */
    public static function fromWpRestRequest(WP_REST_Request $wpRestRequest): Request
    {
        return new static($wpRestRequest->method, $wpRestRequest->route, $wpRestRequest->attributes);
    }

    public function getHeader(string $key, ?string $default = null): ?string
    {
        return $this->get_header($key) ?? $default;
    }

    /**
     * @inheritdoc
     */
    public function getHeaders(): array
    {
        return $this->get_headers();
    }

    public function hasHeader(string $key): bool
    {
        return $this->getHeader($key) !== null;
    }

    public function removeHeader(string $key): Request
    {
        if ($this->hasHeader($key)) {
            $this->remove_header($key);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeHeaders(array $headers): Request
    {
        foreach ($headers as $header) {
            $this->removeHeader($header);
        }

        return $this;
    }

    public function setHeader(string $key, string $value, bool $override = false): Request
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
    public function setHeaders(array $headers, bool $override = false): Request
    {
        $this->set_headers($headers, $override);

        return $this;
    }
}
