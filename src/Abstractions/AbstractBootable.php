<?php

namespace Wordless\Abstractions;

abstract class AbstractBootable
{
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'rest_api_init';
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'action';

    abstract public static function register();

    public static function boot(int $hook_priority = 10)
    {
        $hook_addition_function = 'add_' . static::TYPE;
        $hook_addition_function(self::HOOK, [static::class, 'register'], $hook_priority, 0);
    }
}