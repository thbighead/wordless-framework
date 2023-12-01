<?php

namespace Wordless\Application\Listeners;

use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Enums\AdminMenuItemPosition;
use Wordless\Wordpress\Enums\DashIcon;
use Wordless\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Wordpress\Hook\Enums\Action;

class ShowCustomFrontPageAtAdminSideMenu extends ActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'showAsDetachedMenuItem';

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

    protected static function hook(): ActionHook
    {
        return Action::admin_menu;
    }
}
