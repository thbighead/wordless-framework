<?php

namespace Wordless\Wordpress\Models\PostType\Traits;

use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;

trait Repository
{
    /**
     * @return static[]
     */
    public static function getAllCustom(): array
    {
        $customPostTypes = [];

        foreach (get_post_types(['_builtin' => false]) as $custom_post_type_key) {
            try {
                $customPostTypes[] = new static($custom_post_type_key);
            } catch (PostTypeNotRegistered) {
                continue;
            }
        }

        return $customPostTypes;
    }
}
