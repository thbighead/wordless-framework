<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DirectoryFiles\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class NotAPhpFile extends InvalidArgumentException
{
    public function __construct(public readonly string $wrong_file_path, ?Throwable $previous = null)
    {
        parent::__construct(
            "The file at $this->wrong_file_path does not seems to be a PHP file (its extensions isn't '.php').",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
