<?php declare(strict_types=1);

namespace Wordless\Application\Commands\PublishConfigurationFiles\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToPublishConfigFilesFromCommandArgument extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Could not publish configuration file.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
