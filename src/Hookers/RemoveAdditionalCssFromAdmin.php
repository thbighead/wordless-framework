<?php

namespace App\Hookers;

use Wordless\Abstractions\AbstractHooker;
use WP_Customize_Manager;

class RemoveAdditionalCssFromAdmin extends AbstractHooker
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 1;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'removeAdditionalCss';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'customize_register';
    /**
     * WordPress action|filter hook priority
     */
    protected const HOOK_PRIORITY = 15;

    public static function removeAdditionalCss(WP_Customize_Manager $manager)
    {
        $manager->remove_section('custom_css');
    }
}
