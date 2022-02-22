<?php

namespace Wordless\Abstractions;

abstract class AbstractHooker
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
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'action';

    public static function boot()
    {
        $hook_addition_function = 'add_' . static::TYPE;
        $hook_addition_function(
            static::HOOK,
            [static::class, static::FUNCTION],
            static::HOOK_PRIORITY,
            static::ACCEPTED_NUMBER_OF_ARGUMENTS
        );
    }
}