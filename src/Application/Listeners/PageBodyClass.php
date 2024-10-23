<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Infrastructure\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Enums\Filter;
use WP_Post;

class PageBodyClass extends FilterListener
{
    /**
     * The public static method which shall be executed during hook.
     */
    protected const FUNCTION = 'addPagesSlugBodyClass';

    public static function addPagesSlugBodyClass(array $body_classes): array
    {
        global $post;

        if (!($post instanceof WP_Post)) {
            return $body_classes;
        }

        if (is_page()) {
            $body_classes[] = "page-$post->post_name";
        }

        return $body_classes;
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }

    protected static function hook(): FilterHook
    {
        return Filter::body_class;
    }
}
