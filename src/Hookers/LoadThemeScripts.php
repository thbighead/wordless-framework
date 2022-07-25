<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\AbstractHooker;

class LoadThemeScripts extends AbstractHooker
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 0;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'loadMainScripts';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'wp_enqueue_scripts';
    /**
     * WordPress action|filter hook priority
     */
    protected const HOOK_PRIORITY = 20;
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'action';

    public static function loadMainScripts()
    {
        wp_enqueue_style(
            'infobase-theme',
            get_stylesheet_directory_uri() . '/public/css/main.css',
            [],
        );

        wp_enqueue_script(
            'main',
            get_stylesheet_directory_uri() . '/public/js/main.js',
            [],
        );
    }
}
