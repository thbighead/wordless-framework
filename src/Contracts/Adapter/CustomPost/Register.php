<?php

namespace Wordless\Contracts\Adapter\CustomPost;

use Wordless\Abstractions\Guessers\CustomPostTypeKeyGuesser;
use Wordless\Exceptions\InvalidCustomPostTypeKey;

trait Register
{
    use Labels, Rewrite, Validation;

    /**
     * @return void
     * @throws InvalidCustomPostTypeKey
     */
    public static function register()
    {
        if (static::TYPE_KEY === null) {
            $guesser = new CustomPostTypeKeyGuesser(static::class);
            register_post_type(static::$type_key = $guesser->getValue(), self::mountArguments());

            return;
        }

        self::validateTypeKey();

        register_post_type(static::$type_key = static::TYPE_KEY, self::mountArguments());
    }

    protected static function mountArguments(): array
    {
        $arguments = [
            'public' => static::isPublic(),
            'exclude_from_search' => static::isExcludedFromSearch(),
            'publicly_queryable' => static::isPubliclyUrlQueryable(),
            'show_ui' => static::isShownInAdminPanel(),
            'show_in_nav_menus' => static::isVisibleInAdminPanelNavigationMenuSelection(),
            'show_in_menu' => static::isListedInAdminPanelMenu(),
            'show_in_admin_bar' => static::isVisibleInAdminPanelMenuBar(),
            'menu_icon' => static::getAdminMenuIcon(),
            'hierarchical' => static::isHierarchical(),
            'has_archive' => static::hasArchive(),
            'rewrite' => static::mountRewriteArguments(),
            'delete_with_user' => static::shouldDeletePostWhenAuthorIsDeleted(),
            'show_in_rest' => static::shouldRegisterRestApiEndpoints(),
        ];

        if (!empty($labels = static::customizeLabels() + self::mountDefaultLabels())) {
            $arguments['labels'] = $labels;
        }

        if (($description = static::description()) !== null) {
            $arguments['description'] = $description;
        }

        if (($menu_position = static::getAdminMenuPosition()) !== null) {
            $arguments['menu_position'] = $menu_position;
        }

        if (($capability_type = static::getCapabilityType()) !== null) {
            $arguments['capability_type'] = $capability_type;
        }

        if (($map_meta_cap = static::shouldMapMetaCapability()) !== null) {
            $arguments['map_meta_cap'] = $map_meta_cap;
        }

        if (($supports = static::postFields()) !== null) {
            $arguments['supports'] = $supports;
        }

        if (!empty($taxonomies = static::postTaxonomies())) {
            $arguments['taxonomies'] = $taxonomies;
        }

        if (!($permalink_epmask = static::apiPermalink()) !== null) {
            $arguments['permalink_epmask'] = $permalink_epmask;
        }

        if (!($query_var = static::getUrlQueryParameterName()) !== null) {
            $arguments['query_var'] = $query_var;
        }

        if (!($can_export = static::canBeExported()) !== null) {
            $arguments['can_export'] = $can_export;
        }

        if (!($rest_base = static::getRestApiBaseUrl()) !== null) {
            $arguments['rest_base'] = $rest_base;
        }

        if (!($rest_controller_class = static::controller()) !== null) {
            $arguments['rest_controller_class'] = $rest_controller_class;
        }

        return $arguments;
    }
}
