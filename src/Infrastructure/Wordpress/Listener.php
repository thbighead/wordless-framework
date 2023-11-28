<?php

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Infrastructure\Wordpress\Listener\Enums\HookType;

abstract class Listener
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 0;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'register';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'rest_api_init';
    /**
     * WordPress action|filter hook priority
     */
    protected const HOOK_PRIORITY = 10;

    public static function hookIt(): void
    {
        $hook_addition_function = 'add_' . static::type()->name;
        $hook_addition_function(
            static::HOOK,
            [static::class, static::FUNCTION],
            static::HOOK_PRIORITY,
            static::ACCEPTED_NUMBER_OF_ARGUMENTS
        );
    }

    protected static function type(): HookType
    {
        return HookType::action;
    }
}
