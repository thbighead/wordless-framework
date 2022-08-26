<?php

namespace Wordless\Adapters;

use WP_User;

class User extends WP_User
{
    public function __construct(?WP_User $wp_user = null)
    {
        parent::__construct($wp_user ?? wp_get_current_user());
    }

    public function can(string $capability, ...$for_id): bool
    {
        return $this->has_cap($capability, ...$for_id);
    }
}