<?php declare(strict_types=1);

namespace Wordless\Application\Commands\WordlessInstall\Traits\ForFramework\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToGenerateEmptyWordlessThemeException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to generate empty Wordless Theme.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
