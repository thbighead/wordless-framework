<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\ProjectPath\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToGetWordpressTheme extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to get Wordpress active theme from Wordless config files.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
