<?php

namespace Wordless\Abstractions;

abstract class AbstractBootable
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'register';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'rest_api_init';
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'action';

    public static function boot(int $hook_priority = 10, int $accepted_number_of_args = 0)
    {
        $hook_addition_function = 'add_' . static::TYPE;
        $hook_addition_function(
            self::HOOK,
            [static::class, static::FUNCTION],
            $hook_priority,
            $accepted_number_of_args
        );
    }
}