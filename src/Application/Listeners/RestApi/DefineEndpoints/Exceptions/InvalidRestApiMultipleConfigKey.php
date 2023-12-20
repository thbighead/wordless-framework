<?php

namespace Wordless\Application\Listeners\RestApi\DefineEndpoints\Exceptions;

use DomainException;
use Throwable;
use Wordless\Application\Providers\RestApiProvider;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidRestApiMultipleConfigKey extends DomainException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Can\'t set "'
            . RestApiProvider::CONFIG_ROUTES_KEY_DISALLOW
            . '" and "'
            . RestApiProvider::CONFIG_ROUTES_KEY_ALLOW
            . '" together in rest-api.'
            . RestApiProvider::CONFIG_KEY_ROUTES,
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
