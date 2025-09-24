<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Config\Traits\Internal\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToLoadConfigFile extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            "Couldn't load config file. {$previous->getMessage()}",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
