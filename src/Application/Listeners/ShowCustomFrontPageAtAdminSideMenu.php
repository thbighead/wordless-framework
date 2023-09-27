<?php

namespace Wordless\Application\Listeners;

use Wordless\Infrastructure\Wordpress\Hooker;
use Wordless\Wordpress\Enums\AdminMenuItemPosition;
use Wordless\Wordpress\Enums\DashIcon;

class ShowCustomFrontPageAtAdminSideMenu extends Hooker
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'showAsDetachedMenuItem';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'admin_menu';

    public static function showAsDetachedMenuItem(): void
    {
        $front_page_id = get_option('page_on_front');

        if (!is_numeric($front_page_id)) {
            return;
        }

        $front_page_id = (int)$front_page_id;

        if ($front_page_id > 0) {
            add_menu_page(
                $home_page_admin_label = __('Front Page'),
                $home_page_admin_label,
                'edit_published_posts',
                "post.php?action=edit&post=$front_page_id",
                null,
                DashIcon::home->value,
                AdminMenuItemPosition::AFTER_FIRST_SEPARATOR->value
            );
        }
    }
}
