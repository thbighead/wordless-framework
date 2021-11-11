<?php

namespace Wordless\Abstractions;

abstract class AbstractBootable
{
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'action';
    abstract protected static function register();

    public static function boot($hook_priority = 10)
    {
        $hook_addition_function = 'add_' . static::TYPE;
        $hook_addition_function(static::class, 'register', $hook_priority, 0);
    }
}