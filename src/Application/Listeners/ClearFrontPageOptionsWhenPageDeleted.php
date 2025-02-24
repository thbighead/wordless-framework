<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Option;
use Wordless\Application\Helpers\Option\Exception\FailedToUpdateOption;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;
use Wordless\Wordpress\Models\Page\FrontPage;
use WP_Post;

class ClearFrontPageOptionsWhenPageDeleted extends ActionListener
{
    /**
     * The public static method which shall be executed during hook.
     */
    protected const FUNCTION = 'clearFrontPageOptionsIfPageIsDeleted';

    /**
     * @param int $post_id
     * @param WP_Post $post
     * @return void
     * @throws FailedToUpdateOption
     */
    public static function clearFrontPageOptionsIfPageIsDeleted(int $post_id, WP_Post $post): void
    {
        if ($post->post_type === FrontPage::postType()->name && $post_id === Option::get(FrontPage::OPTION_KEY_FRONT_PAGE_ID)){
            Option::delete(FrontPage::OPTION_KEY_FRONT_PAGE_ID);
            Option::createUpdateOrFail(FrontPage::OPTION_KEY_SHOW_ON_FRONT, 'posts');
        }
    }

    protected static function hook(): ActionHook
    {
        return Action::after_delete_post;
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 2;
    }
}
