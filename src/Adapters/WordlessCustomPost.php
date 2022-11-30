<?php

namespace Wordless\Adapters;

use stdClass;
use Wordless\Abstractions\Guessers\CustomPostTypeKeyGuesser;
use Wordless\Contracts\Adapter\WordlessCustomPost\Register;
use Wordless\Exceptions\PostTypeNotRegistered;
use Wordless\Helpers\Str;
use WP_Post;

abstract class WordlessCustomPost extends Post
{
    use Register;

    public const POST_TYPE_KEY_MAX_LENGTH = 20;
    protected const TYPE_KEY = null;
    private static ?string $type_key = null;

    private PostType $type;

    /**
     * @param WP_Post|int $post
     * @param bool $with_acfs
     * @throws PostTypeNotRegistered
     */
    public function __construct($post, bool $with_acfs = true)
    {
        parent::__construct($post, $with_acfs);

        $this->type = new PostType($this->post_type);
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#description-2
     * @return string|null
     */
    public static function description(): ?string
    {
        return null;
    }

    public static function getTypeKey()
    {
        if (static::$type_key !== null) {
            return static::$type_key;
        }

        if (static::TYPE_KEY === null) {
            $guesser = new CustomPostTypeKeyGuesser(static::class);

            return static::$type_key = $guesser->getValue();
        }

        return static::$type_key = static::TYPE_KEY;
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
     * @return bool
     */
    public static function isListedInAdminPanelMenu(): bool
    {
        return static::isShownInAdminPanel();
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_post_type/#show_in_admin_bar
     * @return bool
     */
    public static function isVisibleInAdminPanelMenuBar(): bool
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
        return null; // automagically controlled by WP
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
        return true;
    }

    public static function getPermissions(): array
    {
        try {
            return (array)((new PostType(self::getTypeKey()))->cap ?? new stdClass);
        } catch (PostTypeNotRegistered $exception) {
            return [];
        }
    }

    /**
     * @return PostType
     */
    public function getType(): PostType
    {
        return $this->type;
    }
}
