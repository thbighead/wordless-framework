<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register;

/**
 * https://developer.wordpress.org/reference/functions/register_taxonomy/#arguments
 */
trait Rewrite
{
    /**
     * ‘ep_mask’ – (Required for pretty permalinks) Assign an endpoint mask for this taxonomy – defaults to EP_NONE.
     * If you do not specify the EP_MASK, pretty permalinks will not work. For more info see:
     * https://make.wordpress.org/plugins/2012/06/07/rewrite-endpoints-api/
     * @return int|null
     */
    protected static function apiRoute(): ?int
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
     * ‘hierarchical’ – true or false allow hierarchical urls (implemented in Version 3.1)
     * @return bool|null
     */
    protected static function hierarchicalRoute(): ?bool
    {
        return null;
    }

    /**
     * ‘slug’ – Used as pretty permalink text (i.e. /tag/) – defaults to $taxonomy (taxonomy’s name slug)
     * Should be translatable.
     * @return string|null
     */
    protected static function routeSlug(): ?string
    {
        return null;
    }

    /**
     * ‘with_front’ – allowing permalinks to be prepended with front base – defaults to true
     * @return bool|null
     */
    protected static function shouldRouteBePrefixed(): ?bool
    {
        return null;
    }

    /**
     * @return true|array<string,int|bool|string>
     */
    private static function mountRewriteArguments(): true|array
    {
        $rewrite_arguments = [];

        if (($apiRoute = static::apiRoute()) !== null) {
            $rewrite_arguments['ep_mask'] = $apiRoute;
        }

        if (($hierarchicalRoute = static::hierarchicalRoute()) !== null) {
            $rewrite_arguments['hierarchical'] = $hierarchicalRoute;
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
