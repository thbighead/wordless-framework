<?php

namespace Wordless\Infrastructure\ApiController\Exceptions;

use Exception;
use Throwable;
use Wordless\Enums\ExceptionCode;
use Wordless\Infrastructure\ApiController;

class FailedToGetControllerPathFromCachedData extends Exception
{
    public function __construct(private readonly array $controller_cached_data, ?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to find \''
            . ApiController::CACHE_PATH_KEY
            . '\' key into the following cached data: '
            . var_export($this->controller_cached_data, true),
            ExceptionCode::caught_internally->value,
            $previous
        );
    }

    public function getControllerCachedData(): array
    {
        return $this->controller_cached_data;
    }
}
