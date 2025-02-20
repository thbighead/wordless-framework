<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User\Traits\Crud\Traits;

use Wordless\Wordpress\Models\User\Traits\Crud\Traits\Update\Exceptions\FailedToUpdateUser;
use WP_Error;

trait Update
{
    /**
     * @return $this
     * @throws FailedToUpdateUser
     */
    public function save(): static
    {
        if (($result = wp_update_user($this)) instanceof WP_Error) {
            throw new FailedToUpdateUser($this, $result);
        }

        return $this;
    }
}
