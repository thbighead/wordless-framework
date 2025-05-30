<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User\Traits\Crud\Traits;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Wordpress\Models\User\Traits\Crud\Traits\Delete\Exceptions\FailedToDeleteUser;

trait Delete
{
    /**
     * @return void
     * @throws FailedToDeleteUser
     */
    public function delete(): void
    {
        try {
            self::requireWordpressAdminUserScript();
        } catch (PathNotFoundException $exception) {
            throw new FailedToDeleteUser($this, $exception);
        }

        if (!wp_delete_user($this->id())) {
            throw new FailedToDeleteUser($this);
        }
    }
}
