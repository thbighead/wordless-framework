<?php

namespace Wordless\Adapters;

use Wordless\Contracts\Adapter\WordlessCustomPost\Register;
use WP_Post;
use WP_Post_Type;

abstract class WordlessCustomPost extends Post
{
    use Register;

    public const POST_TYPE_KEY_MAX_LENGTH = 20;
    protected const TYPE_KEY = null;

    private WP_Post_Type $type;

    /**
     * @param WP_Post|int $post
     * @param bool $with_acfs
     */
    public function __construct($post, bool $with_acfs = true)
    {
        parent::__construct($post, $with_acfs);

        $this->type = get_post_type_object($this->post_type);
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#description-2
     * @return string|null
     */
    public static function description(): ?string
    {
        return null;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#has_archive
     * @return bool
     */
    public static function hasArchive(): bool
    {
        return false;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#exclude_from_search
     * @return bool
     */
    public static function isExcludedFromSearch(): bool
    {
        return !static::isPublic();
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#hierarchical
     * @return bool
     */
    public static function isHierarchical(): bool
    {
        return false;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#public
     * @return bool
     */
    public static function isPublic(): bool
    {
        return false;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#publicly_queryable
     * @return bool
     */
    public static function isPubliclyUrlQueryable(): bool
    {
        return static::isPublic();
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#show_ui
     * @return bool
     */
    public static function isShownInAdminPanel(): bool
    {
        return static::isPublic();
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#show_in_menu
     * @return bool|string
     */
    public static function isListedInAdminPanelMenu()
    {
        return static::isShownInAdminPanel();
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#show_in_admin_bar
     * @return bool|string
     */
    public static function isVisibleInAdminPanelMenuBar()
    {
        return static::isShownInAdminPanel();
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#show_in_nav_menus
     * @return bool
     */
    public static function isVisibleInAdminPanelNavigationMenuSelection(): bool
    {
        return static::isShownInAdminPanel();
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
     * Use CustomPostTypeMenuPosition constants to set it easily.
     * @return int|null
     */
    protected static function getAdminMenuPosition(): ?int
    {
        return null; // bellow Comments
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#capability_type
     * @return string|string[]|null
     */
    protected static function getCapabilityType()
    {
        if (($singular_name = static::singularName()) === null) {
            return null;
        }
        if (($plural_name = static::pluralName()) === null) {
            return null;
        }

        return [$singular_name, $plural_name];
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
    protected static function getUrlQueryParameterName()
    {
        return null;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#show_in_rest
     * @return bool
     */
    protected static function shouldRegisterRestApiEndpoints(): bool
    {
        return true;
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#supports
     * Use CustomPostTypeMenuField constants to set it easily.
     * @return null
     */
    protected static function postFields()
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
        return null;
    }

    /**
     * @return WP_Post_Type
     */
    public function getType(): WP_Post_Type
    {
        return $this->type;
    }
}
