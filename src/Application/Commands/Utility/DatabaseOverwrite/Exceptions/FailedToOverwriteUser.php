<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\DatabaseOverwrite\Exceptions;

use Exception;
use Throwable;
use Wordless\Application\Commands\Utility\DatabaseOverwrite\DTO\UserDTO;

class FailedToOverwriteUser extends Exception
{
    public function __construct(public readonly UserDTO $user, ?Throwable $previous = null)
    {
        parent::__construct("Failed to overwrite user with id {$this->user->id}", 0, $previous);
    }
}
