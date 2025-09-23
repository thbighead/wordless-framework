<?php declare(strict_types=1);

namespace Wordless\Application\Commands\PublishConfigurationFiles\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class PublishConfigurationFilesFailed extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Could not publish configuration files.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
