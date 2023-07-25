<?php

namespace App\Hookers;

use Wordless\Abstractions\Hooker;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;
use WP_Error;

class SetRestApiIsAllowed extends Hooker
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 1;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'restApiIsEnabled';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'rest_authentication_errors';
    /**
     * WordPress action|filter hook priority
     */
    protected const HOOK_PRIORITY = 20;
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

    /**
     * @param WP_Error|true|null $errors
     * @throws PathNotFoundException
     */
    public static function restApiIsEnabled($errors)
    {
        if (Config::tryToGetOrDefault('rest-api.enable') === false) {
            return new WP_Error('rest_disabled', __('The WordPress REST API has been disabled.'));
        }

        return $errors;
    }
}
