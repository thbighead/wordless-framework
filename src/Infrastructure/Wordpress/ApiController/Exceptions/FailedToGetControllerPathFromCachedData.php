<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\ApiController\Exceptions;

use Exception;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Wordpress\ApiController;

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
