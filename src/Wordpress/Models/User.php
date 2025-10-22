<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData;
use Wordless\Wordpress\Models\Role\Enums\StandardRole;
use Wordless\Wordpress\Models\Traits\Terms;
use Wordless\Wordpress\Models\User\Exceptions\NoUserAuthenticated;
use Wordless\Wordpress\Models\User\Traits\Crud;
use WP_User;

class User extends WP_User implements IRelatedMetaData
{
    use Crud;
    use Terms;
    use WithMetaData;

    public static function objectType(): ObjectType
    {
        return ObjectType::user;
    }

    /**
     * @return void
     * @throws PathNotFoundException
     */
    private static function requireWordpressAdminUserScript(): void
    {
        require_once ProjectPath::wpCore('wp-admin/includes/user.php');
    }

    /**
     * @param WP_User|null $wp_user
     * @throws NoUserAuthenticated
     */
    public function __construct(?WP_User $wp_user = null)
    {
        if (($wp_user = $wp_user ?? wp_get_current_user()) === null) {
            throw new NoUserAuthenticated;
        }

        parent::__construct($wp_user);
    }

    public function can(string $capability, ...$for_id): bool
    {
        return $this->has_cap($capability, ...$for_id);
    }

    public function hasRole(Role|StandardRole|string $role): bool
    {
        return Arr::hasValue($this->roles, match (true) {
            $role instanceof Role => $role->name,
            $role instanceof StandardRole => $role->value,
            default => $role,
        });
    }

    public function toArray(): array
    {
        return $this->to_array();
    }

    final public function id(): int
    {
        return $this->ID;
    }
}
