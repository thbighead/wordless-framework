<?php

namespace Wordless\Adapters;

use Wordless\Contracts\Adapter\WithAcfs;
use WP_User;

class User extends WP_User
{
    use WithAcfs;

    public function __construct(?WP_User $wp_user = null, bool $with_acfs = true)
    {
        parent::__construct($wp_user ?? wp_get_current_user());

        if ($with_acfs) {
            $this->loadUserAcfs($this->ID);
        }
    }

    public function can(string $capability, ...$for_id): bool
    {
        return $this->has_cap($capability, ...$for_id);
    }

    private function loadUserAcfs(int $from_id)
    {
        $this->loadAcfs("user_$from_id");
    }
}