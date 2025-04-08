<?php declare(strict_types=1);

namespace Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory\FileWriter\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FileWritingError extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to write content in file.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
