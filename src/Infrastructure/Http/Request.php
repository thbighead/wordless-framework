<?php

namespace Wordless\Infrastructure\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Wordless\Application\Libraries\DesignPattern\Singleton\Traits\Constructors;

class Request extends SymfonyRequest implements RequestInterface
{
    use Constructors;

    protected static function newInstance(): static
    {
        return static::createFromGlobals();
    }

    public function withProtocolVersion(string $version)
    {
        $this;
    }

    public function getHeaders()
    {
        // TODO: Implement getHeaders() method.
    }

    public function hasHeader(string $name)
    {
        // TODO: Implement hasHeader() method.
    }

    public function getHeader(string $name)
    {
        // TODO: Implement getHeader() method.
    }

    public function getHeaderLine(string $name)
    {
        // TODO: Implement getHeaderLine() method.
    }

    public function withHeader(string $name, $value)
    {
        // TODO: Implement withHeader() method.
    }

    public function withAddedHeader(string $name, $value)
    {
        // TODO: Implement withAddedHeader() method.
    }

    public function withoutHeader(string $name)
    {
        // TODO: Implement withoutHeader() method.
    }

    public function getBody()
    {
        // TODO: Implement getBody() method.
    }

    public function withBody(StreamInterface $body)
    {
        // TODO: Implement withBody() method.
    }

    public function getRequestTarget()
    {
        // TODO: Implement getRequestTarget() method.
    }

    public function withRequestTarget(string $requestTarget)
    {
        // TODO: Implement withRequestTarget() method.
    }

    public function withMethod(string $method)
    {
        // TODO: Implement withMethod() method.
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false)
    {
        // TODO: Implement withUri() method.
    }
}
