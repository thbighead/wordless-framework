<?php

namespace Wordless\Application\Listeners;

use Wordless\Infrastructure\Wordpress\Hooker;

class HideContentEditorForCustomFrontPageAtAdmin extends Hooker
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'removeContentEditorFeatureFromCustomFrontPage';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'admin_init';

    public static function removeContentEditorFeatureFromCustomFrontPage()
    {
        $post_id = $_GET['post'] ?: $_POST['post_ID'];

        if (!is_numeric($post_id)) {
            return;
        }

        $front_page_id = get_option('page_on_front');

        if (!is_numeric($front_page_id)) {
            return;
        }

        $post_id = (int)$post_id;
        $front_page_id = (int)$front_page_id;

        if ($front_page_id > 0 && $post_id > 0 && $front_page_id === $post_id) {
            remove_post_type_support('page', 'editor');
        }
    }
}
