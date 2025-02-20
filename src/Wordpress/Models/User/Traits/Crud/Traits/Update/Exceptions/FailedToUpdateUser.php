<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User\Traits\Crud\Traits\Update\Exceptions;

use Throwable;
use Wordless\Exceptions\WpErrorException;
use Wordless\Wordpress\Models\User;
use WP_Error;

class FailedToUpdateUser extends WpErrorException
{
    public function __construct(
        public readonly User $user,
        WP_Error             $requestError,
        ?Throwable           $previous = null
    )
    {
        parent::__construct($requestError, $previous);
    }

    protected function mountMessage(): string
    {
        return "Failed to update user with id {$this->user->id()} due to the following errors: "
            . parent::mountMessage();
    }
}
