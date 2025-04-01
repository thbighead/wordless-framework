<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Wordpress\Enums\ObjectType;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData;
use Wordless\Wordpress\Models\Traits\WithAcfs;
use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;
use Wordless\Wordpress\Models\User\Exceptions\NoUserAuthenticated;
use Wordless\Wordpress\Models\User\Traits\Crud;
use WP_User;

class User extends WP_User implements IRelatedMetaData
{
    use Crud;
    use WithAcfs;
    use WithMetaData;

    public static function objectType(): ObjectType
    {
        return ObjectType::user;
    }

    /**
     * @return void
     * @throws PathNotFoundException
     */
    private static function requireWordpressUserScript(): void
    {
        require_once ProjectPath::wpCore('wp-admin/includes/user.php');
    }

    /**
     * @param WP_User|null $wp_user
     * @param bool $with_acfs
     * @throws InvalidAcfFunction
     * @throws NoUserAuthenticated
     */
    public function __construct(?WP_User $wp_user = null, bool $with_acfs = true)
    {
        if (($wp_user = $wp_user ?? wp_get_current_user()) === null) {
            throw new NoUserAuthenticated;
        }

        parent::__construct($wp_user);

        if ($with_acfs) {
            $this->loadUserAcfs($this->ID);
        }
    }

    public function can(string $capability, ...$for_id): bool
    {
        return $this->has_cap($capability, ...$for_id);
    }

    public function toArray(): array
    {
        $user_as_array = $this->to_array();

        if (!empty($this->getAcfs())) {
            $user_as_array['acfs'] = $this->getAcfs();
        }

        return $user_as_array;
    }

    final public function id(): int
    {
        return $this->ID;
    }

    /**
     * @param int $from_id
     * @return void
     * @throws InvalidAcfFunction
     */
    private function loadUserAcfs(int $from_id): void
    {
        $this->loadAcfs("user_$from_id");
    }
}
