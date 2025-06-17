<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\EnvironmentOnAdminBar\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToAddEnvironmentFlagToAdminMenu extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to add environment flag to admin menu.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
