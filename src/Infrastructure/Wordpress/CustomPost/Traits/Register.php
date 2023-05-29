<?php

namespace Wordless\Infrastructure\Wordpress\CustomPost\Traits;

use Wordless\Application\Guessers\CustomPostTypeKeyGuesser;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\DTO\FieldsSupportedArrayDTO;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Enums\CustomPostTypeMenuPosition;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Exceptions\CustomPostTypeRegistrationFailed;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Labels;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Rewrite;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\InvalidCustomPostTypeKey;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostTypeKey;
use WP_Error;

trait Register
{
    use Labels, Rewrite, Validation;

    /** @var array<static, string> */
    private static array $type_keys = [];

    final public static function getTypeKey(): string
    {
        return self::$type_keys[static::class] ??
            self::$type_keys[static::class] = static::TYPE_KEY ??
                (new CustomPostTypeKeyGuesser(static::class))->getValue();
    }

    /**
     * @return void
     * @throws CustomPostTypeRegistrationFailed
     * @throws InvalidCustomPostTypeKey
     * @throws ReservedCustomPostTypeKey
     */
    final public static function register(): void
    {
        self::validateTypeKey();

        if (($registrationResult = register_post_type(
                static::getTypeKey(),
                static::mountArguments()
            )) instanceof WP_Error) {
            throw new CustomPostTypeRegistrationFailed($registrationResult);
        }
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#permalink_epmask
     * @return string|null
     */
    protected static function apiPermalink(): ?string
    {
        return null;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#can_export
     * @return bool|null
     */
    protected static function canBeExported(): ?bool
    {
        return null;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#rest_controller_class
     * @return string|null
     */
    protected static function controller(): ?string
    {
        return null; // automagically controlled by WP
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#description-2
     * @return string|null
     */
    protected static function description(): ?string
    {
        return null;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#menu_icon
     * Use DashIcon helper to set it easily with default WordPress admin dashicons
     * @return string|null
     */
    protected static function getAdminMenuIcon(): ?string
    {
        return null; // posts icon
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#menu_position
     * @return CustomPostTypeMenuPosition|null
     */
    protected static function getAdminMenuPosition(): ?CustomPostTypeMenuPosition
    {
        return null; // bellow Comments
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#capability_type
     * @return string[]|null
     */
    protected static function getCapabilityType(): ?array
    {
        if (($singular_name = static::singularName()) === null) {
            return null;
        }
        if (($plural_name = static::pluralName()) === null) {
            return null;
        }

        return [Str::slugCase($singular_name), Str::slugCase($plural_name)];
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#rest_base
     * @return string|null
     */
    protected static function getRestApiBaseUrl(): ?string
    {
        return null;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#query_var
     * @return bool|string|null
     */
    protected static function getUrlQueryParameterName(): bool|string|null
    {
        return null;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#has_archive
     * @return bool
     */
    protected static function hasArchive(): bool
    {
        return false;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#exclude_from_search
     * @return bool
     */
    protected static function isExcludedFromSearch(): bool
    {
        return !static::isPublic();
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#hierarchical
     * @return bool
     */
    protected static function isHierarchical(): bool
    {
        return false;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#public
     * @return bool
     */
    protected static function isPublic(): bool
    {
        return false;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#publicly_queryable
     * @return bool
     */
    protected static function isPubliclyUrlQueryable(): bool
    {
        return static::isPublic();
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#show_ui
     * @return bool
     */
    protected static function isShownInAdminPanel(): bool
    {
        return static::isPublic();
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#show_in_menu
     * @return bool
     */
    protected static function isListedInAdminPanelMenu(): bool
    {
        return static::isShownInAdminPanel();
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#show_in_admin_bar
     * @return bool
     */
    protected static function isVisibleInAdminPanelMenuBar(): bool
    {
        return static::isShownInAdminPanel();
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#show_in_nav_menus
     * @return bool
     */
    protected static function isVisibleInAdminPanelNavigationMenuSelection(): bool
    {
        return static::isShownInAdminPanel();
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
            $arguments['menu_position'] = $menu_position->value;
        }

        if (($capability_type = static::getCapabilityType()) !== null) {
            $arguments['capability_type'] = $capability_type;
        }

        if (($map_meta_cap = static::shouldMapMetaCapability()) !== null) {
            $arguments['map_meta_cap'] = $map_meta_cap;
        }

        if (($supports = static::postFields()) !== null) {
            $arguments['supports'] = $supports->getSupported();
        }

        if (!empty($taxonomies = static::postTaxonomies())) {
            $arguments['taxonomies'] = $taxonomies;
        }

        if (($permalink_epmask = static::apiPermalink()) !== null) {
            $arguments['permalink_epmask'] = $permalink_epmask;
        }

        if (($query_var = static::getUrlQueryParameterName()) !== null) {
            $arguments['query_var'] = $query_var;
        }

        if (($can_export = static::canBeExported()) !== null) {
            $arguments['can_export'] = $can_export;
        }

        if (($rest_base = static::getRestApiBaseUrl()) !== null) {
            $arguments['rest_base'] = $rest_base;
        }

        if (($rest_controller_class = static::controller()) !== null) {
            $arguments['rest_controller_class'] = $rest_controller_class;
        }

        return $arguments;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#supports
     * @return FieldsSupportedArrayDTO|null
     */
    protected static function postFields(): ?FieldsSupportedArrayDTO
    {
        return null;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#taxonomies-2
     * @return string[]
     */
    protected static function postTaxonomies(): array
    {
        return [];
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#delete_with_user
     * @return bool|null
     */
    protected static function shouldDeletePostWhenAuthorIsDeleted(): ?bool
    {
        return null;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#map_meta_cap
     * @return bool|null
     */
    protected static function shouldMapMetaCapability(): ?bool
    {
        return true;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#show_in_rest
     * @return bool
     */
    protected static function shouldRegisterRestApiEndpoints(): bool
    {
        return true;
    }
}
