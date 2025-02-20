<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User\Traits\Crud\Traits;

use Wordless\Application\Helpers\Str;
use Wordless\Wordpress\Models\User\Traits\Crud\Traits\Create\Exceptions\FailedToCreateUser;
use WP_Error;

trait Create
{
    /**
     * @param string $email
     * @param string $password
     * @param string|null $username
     * @return static
     * @throws FailedToCreateUser
     */
    public static function create(string $email, string $password, ?string $username = null): static
    {
        if (($new_user_id = wp_create_user(
                $username ??= Str::before($email, '@'),
                $password,
                $email
            )) instanceof WP_Error) {
            throw new FailedToCreateUser($email, $password, $username, $new_user_id);
        }

        return new static(get_user_by('id', $new_user_id));
    }
}
