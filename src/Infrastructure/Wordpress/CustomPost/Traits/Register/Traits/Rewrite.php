<?php

namespace Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits;

/**
 * https://developer.wordpress.org/reference/functions/register_post_type/#rewrite
 */
trait Rewrite
{
    /**
     * 'ep_mask' => const As of 3.4 Assign an endpoint mask for this post type. More info:
     * https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
     * https://make.wordpress.org/plugins/2012/06/07/rewrite-endpoints-api/
     * @return string|null
     */
    protected static function apiRoute(): ?string
    {
        return null;
    }

    /**
     * 'pages' => bool Should the permalink structure provide for pagination.
     * @return bool|null
     */
    protected static function canBePaginated(): ?bool
    {
        return null;
    }

    /**
     * 'feeds' => bool Should a feed permalink structure be built for this post type.
     * @return bool|null
     */
    protected static function hasFeeds(): ?bool
    {
        return null;
    }

    /**
     * 'slug' => string Customize the permalink structure slug. Defaults to the $post_type value.
     * Should be translatable.
     * @return string|null
     */
    protected static function routeSlug(): ?string
    {
        return null;
    }

    /**
     * 'with_front' => bool Should the permalink structure be prepended with the front base.
     * (example: if your permalink structure is /blog/, then your links will be: false->/news/, true->/blog/news/).
     * @return bool|null
     */
    protected static function shouldRouteBePrefixed(): ?bool
    {
        return null;
    }

    private static function mountRewriteArguments(): bool|array
    {
        $rewrite_arguments = [];

        if (($apiRoute = static::apiRoute()) !== null) {
            $rewrite_arguments['ep_mask'] = $apiRoute;
        }

        if (($canBePaginated = static::canBePaginated()) !== null) {
            $rewrite_arguments['pages'] = $canBePaginated;
        }

        if (($hasFeeds = static::hasFeeds()) !== null) {
            $rewrite_arguments['feeds'] = $hasFeeds;
        }

        if (($routeSlug = static::routeSlug()) !== null) {
            $rewrite_arguments['slug'] = $routeSlug;
        }

        if (($shouldRouteBePrefixed = static::shouldRouteBePrefixed()) !== null) {
            $rewrite_arguments['with_front'] = $shouldRouteBePrefixed;
        }

        return empty($rewrite_arguments) ? true : $rewrite_arguments;
    }
}
