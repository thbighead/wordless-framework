<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Exceptions;

use Exception;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToCreateWordpressAttachment extends Exception
{
    public function __construct(public readonly string $absolute_file_path, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to insert $this->absolute_file_path as an Wordpress attachment into database.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
