<?php

namespace Wordless\Core\Composer\Exceptions;

use Exception;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class AppHostAlreadySetOnDotEnv extends Exception
{
    public function __construct(private readonly string $app_host_already_set, ?Throwable $previous = null)
    {
        parent::__construct(
            "APP_HOST already set in .env file as '$this->app_host_already_set'.",
            ExceptionCode::caught_internally->value,
            $previous
        );
    }

    public function getAppHostAlreadySet(): string
    {
        return $this->app_host_already_set;
    }
}
