<?php

namespace Wordless\Infrastructure\Http;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Wordless\Application\Libraries\DesignPattern\Singleton\Traits\Constructors;
use Wordless\Infrastructure\Http\Request\Enums\Verb;

class Request extends SymfonyRequest
{
    use Constructors;

    protected static function newInstance(): static
    {
        return new self(
            $_GET,
            $_POST,
            cookies: $_COOKIE,
            files: $_FILES,
            server: $_SERVER
        );
    }

    /** @noinspection PhpRedundantMethodOverrideInspection */
    public function __clone()
    {
        parent::__clone();
    }

    public function isMethodVerb(Verb $method): bool
    {
        return parent::isMethod($method->value);
    }

    protected function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
              $content = null
    )
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }
}
