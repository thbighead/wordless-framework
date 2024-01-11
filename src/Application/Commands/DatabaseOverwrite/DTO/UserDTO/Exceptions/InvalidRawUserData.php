<?php declare(strict_types=1);

namespace Wordless\Application\Commands\DatabaseOverwrite\DTO\UserDTO\Exceptions;

use DomainException;
use Throwable;

class InvalidRawUserData extends DomainException
{
    public function __construct(public readonly array $invalid_raw_user_data, ?Throwable $previous = null)
    {
        parent::__construct(
            'The user data passed is invalid: ' . print_r($this->invalid_raw_user_data, true),
            0,
            $previous
        );
    }
}
