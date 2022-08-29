<?php

namespace Wordless\Abstractions;

use App\Hookers\RemoveAdditionalCssFromAdmin;
use App\Hookers\RemoveEmojiFromTinyMce;
use App\Hookers\RemoveEmojiFromWpResourceHints;
use App\Hookers\RemoveGlobalCustomInlineStyles;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

class WpSpeedUp
{
    public const REMOVE_WP_EMOJIS_CONFIG_KEY = 'remove_wp_emojis';
    public const SPEED_UP_WP_CONFIG_KEY = 'speed_up_wp';
    private const WP_FUNCTION_PRINT_EMOJI_DETECTION_SCRIPT = 'print_emoji_detection_script';
    private const WP_FUNCTION_PRINT_EMOJI_STYLES = 'print_emoji_styles';
    private const WP_FUNCTION_WP_STATICIZE_EMOJI = 'wp_staticize_emoji';
    private const WP_HEAD_HOOK_KEY = 'wp_head';

    private static array $admin_configs;

    /**
     * @return array
     * @throws PathNotFoundException
     */
    public static function addAdditionalHooks(): array
    {
        $additional_hooks_configs = [];

        if (self::getAdminConfigs()[static::SPEED_UP_WP_CONFIG_KEY] ?? false) {
            $additional_hooks_configs[] = RemoveAdditionalCssFromAdmin::class;
            $additional_hooks_configs[] = RemoveGlobalCustomInlineStyles::class;
        }

        if (self::getAdminConfigs()[static::REMOVE_WP_EMOJIS_CONFIG_KEY] ?? false) {
            $additional_hooks_configs[] = RemoveEmojiFromTinyMce::class;
            $additional_hooks_configs[] = RemoveEmojiFromWpResourceHints::class;
        }

        return $additional_hooks_configs;
    }

    /**
     * @return array
     * @throws PathNotFoundException
     */
    public static function removeActionsConfigToSpeedUp(): array
    {
        $additional_action_hook_configs = [];

        if (self::getAdminConfigs()[static::SPEED_UP_WP_CONFIG_KEY] ?? false) {
            $additional_action_hook_configs = array_merge_recursive(
                $additional_action_hook_configs,
                self::removeUnnecessaryTagsActionsFromWpHeadConfig(),
            );
        }

        if (self::getAdminConfigs()[static::REMOVE_WP_EMOJIS_CONFIG_KEY] ?? false) {
            $additional_action_hook_configs = array_merge_recursive(
                $additional_action_hook_configs,
                self::removeEmojisActionsConfig(),
                self::removeEmojisFiltersConfig(),
            );
        }

        return $additional_action_hook_configs;
    }

    /**
     * @return array
     * @throws PathNotFoundException
     */
    public static function removeFiltersConfigToSpeedUp(): array
    {
        $additional_filter_hook_configs = [];

        if (self::getAdminConfigs()[static::REMOVE_WP_EMOJIS_CONFIG_KEY] ?? false) {
            $additional_filter_hook_configs = array_merge_recursive(
                $additional_filter_hook_configs,
                self::removeEmojisFiltersConfig(),
            );
        }

        return $additional_filter_hook_configs;
    }

    public static function removeEmojisActionsConfig(): array
    {
        return [
            self::WP_HEAD_HOOK_KEY => [[
                Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => self::WP_FUNCTION_PRINT_EMOJI_DETECTION_SCRIPT,
                Bootstrapper::HOOKERS_REMOVE_TYPE_PRIORITY_CONFIG_KEY => 7,
            ]],
            'admin_print_scripts' => [[
                Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => self::WP_FUNCTION_PRINT_EMOJI_DETECTION_SCRIPT,
            ]],
            'wp_print_styles' => [[
                Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => self::WP_FUNCTION_PRINT_EMOJI_STYLES,
            ]],
            'admin_print_styles' => [[
                Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => self::WP_FUNCTION_PRINT_EMOJI_STYLES,
            ]],
        ];
    }

    public static function removeEmojisFiltersConfig(): array
    {
        return [
            'the_content_feed' => [[
                Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => self::WP_FUNCTION_WP_STATICIZE_EMOJI,
            ]],
            'comment_text_rss' => [[
                Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => self::WP_FUNCTION_WP_STATICIZE_EMOJI,
            ]],
            'wp_mail' => [[
                Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => 'wp_staticize_emoji_for_email',
            ]],
        ];
    }

    public static function removeUnnecessaryTagsActionsFromWpHeadConfig(): array
    {
        return [
            self::WP_HEAD_HOOK_KEY => [
                [
                    Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => 'wp_generator',
                ],
                [
                    Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => 'wp_shortlink_wp_head',
                ],
                [
                    Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => 'feed_links',
                    Bootstrapper::HOOKERS_REMOVE_TYPE_PRIORITY_CONFIG_KEY => 2,
                ],
                [
                    Bootstrapper::HOOKERS_REMOVE_TYPE_FUNCTION_CONFIG_KEY => 'feed_links_extra',
                    Bootstrapper::HOOKERS_REMOVE_TYPE_PRIORITY_CONFIG_KEY => 3,
                ],
            ],
        ];
    }

    /**
     * @return array
     * @throws PathNotFoundException
     */
    private static function getAdminConfigs(): array
    {
        return self::$admin_configs ?? self::$admin_configs = include_once ProjectPath::config(
            'admin.php'
        );
    }
}