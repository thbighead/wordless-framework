<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\AbstractHooker;
use Wordless\Exceptions\InvalidDirectory;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\ProjectPath;

/**
 * Based on https://www.advancedcustomfields.com/resources/local-json/
 */
class AcfLoadLocalGroups extends AbstractHooker
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'load';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'acf/init';

    /**
     * @return void
     * @throws PathNotFoundException
     * @throws InvalidDirectory
     */
    public static function load()
    {
        foreach (DirectoryFiles::listFromDirectory(ProjectPath::acfFieldGroups()) as $acf_group_script_filename) {
            if (!str_ends_with($acf_group_script_filename, '.php')) {
                continue;
            }

            require_once ProjectPath::acfFieldGroups($acf_group_script_filename);
        }
    }
}
