<?php

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits;

use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Register\Labels;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Register\Rewrite;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Register\Validation;
use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Register\Validation\Exceptions\InvalidCustomTaxonomyName;

trait Register
{
    use Labels, Rewrite, Validation;

    /**
     * https://developer.wordpress.org/reference/functions/register_taxonomy/#additional-parameter-information
     * @return string|null
     */
    public static function description(): ?string
    {
        return null;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_taxonomy/#additional-parameter-information
     * @return bool
     */
    public static function isHierarchical(): bool
    {
        return false;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_taxonomy/#additional-parameter-information
     * @return bool
     */
    public static function isPublic(): bool
    {
        return false;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_taxonomy/#additional-parameter-information
     * @return bool
     */
    public static function isPubliclyUrlQueryable(): bool
    {
        return static::isPublic();
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_taxonomy/#additional-parameter-information
     * @return bool
     */
    public static function isListedInAdminPanelMenu(): bool
    {
        return static::isShownInAdminPanel();
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_taxonomy/#additional-parameter-information
     * @return bool
     */
    public static function isShownInAdminPanel(): bool
    {
        return static::isPublic();
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_taxonomy/#additional-parameter-information
     * @return bool
     */
    public static function isVisibleInAdminPanelNavigationMenuSelection(): bool
    {
        return static::isShownInAdminPanel();
    }

    /**
     * @return string[]
     */
    public static function permissions(): array
    {
        if (static::useDefaultPermissions()) {
            return [];
        }

        if (static::isHierarchical()) {
            return [
                'manage_categories',
                'edit_posts',
            ];
        }

        return [
            'manage_terms',
            'edit_terms',
            'delete_terms',
            'assign_terms',
        ];
    }

    /**
     * @return void
     * @throws InvalidCustomTaxonomyName
     */
    public static function register()
    {
        self::validateNameKey();

        register_taxonomy(static::getNameKey(), self::mountArguments());
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_taxonomy/#additional-parameter-information
     * @return string|null
     */
    protected static function controller(): ?string
    {
        return null; // automagically controlled by WP
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_taxonomy/#additional-parameter-information
     * @return string|null
     */
    protected static function getRestApiBaseUrl(): ?string
    {
        return null;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_taxonomy/#additional-parameter-information
     * @return bool|string|null
     */
    protected static function getUrlQueryParameterName()
    {
        return null;
    }

    protected static function shouldAllowAutomaticCreateOnAssociatedPosts(): bool
    {
        return true;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_taxonomy/#additional-parameter-information
     * @return bool
     */
    protected static function shouldRegisterRestApiEndpoints(): bool
    {
        return true;
    }

    protected static function shouldShowInTagCloudWidget(): bool
    {
        return static::isShownInAdminPanel();
    }

    protected static function shouldShowQuickEditInAdmin(): bool
    {
        return static::isShownInAdminPanel();
    }

    protected static function shouldSortAsAddedToObjects(): bool
    {
        return true;
    }

    protected static function useDefaultPermissions(): bool
    {
        return true;
    }

    private static function mountArguments(): array
    {
        $arguments = [
            'hierarchical' => static::isHierarchical(),
            'public' => static::isPublic(),
            'show_ui' => static::isShownInAdminPanel(),
            'show_in_menu' => static::isListedInAdminPanelMenu(),
            'show_in_nav_menus' => static::isVisibleInAdminPanelNavigationMenuSelection(),
            'show_in_rest' => static::shouldRegisterRestApiEndpoints(),
            'show_tagcloud' => static::shouldShowInTagCloudWidget(),
            'show_in_quick_edit' => static::shouldShowQuickEditInAdmin(),
            'show_admin_column' => static::shouldAllowAutomaticCreateOnAssociatedPosts(),
            'rewrite' => static::mountRewriteArguments(),
            'sort' => static::shouldSortAsAddedToObjects(),
        ];

        if (!empty($labels = static::customizeLabels() + self::mountDefaultLabels())) {
            $arguments['labels'] = $labels;
        }

        if (($rest_base = static::getRestApiBaseUrl()) !== null) {
            $arguments['rest_base'] = $rest_base;
        }

        if (($rest_controller_class = static::controller()) !== null) {
            $arguments['rest_controller_class'] = $rest_controller_class;
        }

        if (($description = static::description()) !== null) {
            $arguments['description'] = $description;
        }

        if (($query_var = static::getUrlQueryParameterName()) !== null && $query_var !== true) {
            $arguments['query_var'] = $query_var;
        }

        if (!empty($permissions = static::permissions())) {
            $arguments['capabilities'] = $permissions;
        }

        return $arguments;
    }
}
