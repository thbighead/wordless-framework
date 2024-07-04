<?php

namespace Wordless\Infrastructure\Http;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Wordless\Application\Libraries\DesignPattern\Singleton\Traits\Constructors;

class Request extends SymfonyRequest
{
    use Constructors;

    protected static function newInstance(): static
    {
        return static::createFromGlobals();
    }
}
