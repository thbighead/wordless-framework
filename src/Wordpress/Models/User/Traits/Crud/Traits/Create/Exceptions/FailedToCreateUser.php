<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User\Traits\Crud\Traits\Create\Exceptions;

use Throwable;
use Wordless\Exceptions\WpErrorException;
use WP_Error;

class FailedToCreateUser extends WpErrorException
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $username,
        WP_Error               $requestError,
        ?Throwable             $previous = null
    )
    {
        parent::__construct($requestError, $previous);
    }

    protected function mountMessage(): string
    {
        return "Failed to create a new user with username '$this->username', e-mail '$this->email' and password '$this->password' due to the following errors: "
            . parent::mountMessage();
    }
}
