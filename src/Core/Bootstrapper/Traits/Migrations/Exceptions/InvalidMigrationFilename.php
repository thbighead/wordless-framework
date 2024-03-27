<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions;

use Exception;
use Throwable;

class InvalidMigrationFilename extends Exception
{
    public function __construct(public readonly string $invalid_filename, ?Throwable $previous = null)
    {
        parent::__construct(
            "The filename '$this->invalid_filename' is invalid. A valid name should looks like 'yyyy_MM_dd_hhmmss_my_name.php'.",
            0,
            $previous
        );
    }
}
