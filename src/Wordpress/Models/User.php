<?php

namespace Wordless\Wordpress\Models;

use Wordless\Enums\MetaType;
use Wordless\Infrastructure\Http\RelatedMetaData;
use Wordless\Wordpress\Models\Traits\WithAcfs;
use Wordless\Wordpress\Models\Traits\WithMetaData;
use Wordless\Wordpress\Models\User\Exceptions\NoUserAuthenticated;
use WP_User;

class User extends WP_User implements RelatedMetaData
{
    use WithAcfs, WithMetaData;

    public static function objectType(): string
    {
        return MetaType::USER;
    }

    /**
     * @param WP_User|null $wp_user
     * @param bool $with_acfs
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

    private function loadUserAcfs(int $from_id)
    {
        $this->loadAcfs("user_$from_id");
    }
}
