<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO\FilePathSubjectDTO\Exceptions;

use JsonException;
use Throwable;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO\FilePathSubjectDTO;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidJsonFile extends JsonException
{
    public function __construct(public readonly FilePathSubjectDTO $filePath, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to decode {$this->filePath->getSubject()} data as a JSON.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
