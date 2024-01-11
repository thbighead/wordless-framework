<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\RemoveAdditionalCssFromAdmin;
use Wordless\Application\Listeners\RemoveEmojiFromTinyMce;
use Wordless\Application\Listeners\RemoveEmojiFromWpResourceHints;
use Wordless\Application\Listeners\RemoveGlobalCustomInlineStyles;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO\Exceptions\TriedToSetFunctionWhenRemovingListener;
use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Wordpress\Hook\Enums\Action;
use Wordless\Wordpress\Hook\Enums\Filter;

class WpSpeedUpProvider extends Provider
{
    final public const CONFIG_KEY_REMOVE_WP_EMOJIS = 'remove_wp_emojis';
    final public const CONFIG_KEY_SPEED_UP_WP = 'speed_up_wp';
    private const CONFIG_PREFIX = 'wordpress.admin.';
    private const FUNCTION_PRINT_EMOJI_DETECTION_SCRIPT = 'print_emoji_detection_script';
    private const FUNCTION_PRINT_EMOJI_STYLES = 'print_emoji_styles';
    private const FUNCTION_WP_STATICIZE_EMOJI = 'wp_staticize_emoji';

    /**
     * @return string[]|Listener[]
     * @throws PathNotFoundException
     */
    public function registerListeners(): array
    {
        $additional_hooks_configs = [];

        if (Config::tryToGetOrDefault(self::CONFIG_PREFIX . self::CONFIG_KEY_SPEED_UP_WP, false)) {
            $additional_hooks_configs[] = RemoveAdditionalCssFromAdmin::class;
            $additional_hooks_configs[] = RemoveGlobalCustomInlineStyles::class;
        }

        if (Config::tryToGetOrDefault(self::CONFIG_PREFIX . self::CONFIG_KEY_REMOVE_WP_EMOJIS, false)) {
            $additional_hooks_configs[] = RemoveEmojiFromTinyMce::class;
            $additional_hooks_configs[] = RemoveEmojiFromWpResourceHints::class;
        }

        return $additional_hooks_configs;
    }

    /**
     * @return RemoveHookDTO[]
     * @throws PathNotFoundException
     * @throws TriedToSetFunctionWhenRemovingListener
     */
    public function unregisterActionListeners(): array
    {
        $unregistered_action_listeners = [];

        if (Config::tryToGetOrDefault(self::CONFIG_PREFIX . self::CONFIG_KEY_SPEED_UP_WP, false)) {
            $unregistered_action_listeners[] = RemoveHookDTO::make('wp_head')
                ->setFunction('wp_generator')
                ->setFunction('wp_shortlink_wp_head')
                ->setFunction('feed_links', 2)
                ->setFunction('feed_links_extra', 3);
        }

        if (Config::tryToGetOrDefault(self::CONFIG_PREFIX . self::CONFIG_KEY_REMOVE_WP_EMOJIS, false)) {
            $unregistered_action_listeners[] = RemoveHookDTO::make('wp_head')
                ->setFunction(self::FUNCTION_PRINT_EMOJI_DETECTION_SCRIPT, 7);
            $unregistered_action_listeners[] = RemoveHookDTO::make(Action::admin_print_scripts->value)
                ->setFunction(self::FUNCTION_PRINT_EMOJI_DETECTION_SCRIPT);
            $unregistered_action_listeners[] = RemoveHookDTO::make(Action::admin_print_styles->value)
                ->setFunction(self::FUNCTION_PRINT_EMOJI_STYLES);
            $unregistered_action_listeners[] = RemoveHookDTO::make(Action::admin_print_styles->value)
                ->setFunction(self::FUNCTION_PRINT_EMOJI_STYLES);
        }

        return $unregistered_action_listeners;
    }

    /**
     * @return RemoveHookDTO[]
     * @throws PathNotFoundException
     * @throws TriedToSetFunctionWhenRemovingListener
     */
    public function unregisterFilterListeners(): array
    {
        $unregistered_filter_listeners = [];

        if (Config::tryToGetOrDefault(self::CONFIG_PREFIX . self::CONFIG_KEY_REMOVE_WP_EMOJIS, false)) {
            $unregistered_filter_listeners[] = RemoveHookDTO::make('the_content_feed')
                ->setFunction(self::FUNCTION_WP_STATICIZE_EMOJI);
            $unregistered_filter_listeners[] = RemoveHookDTO::make(Filter::comment_text_rss->value)
                ->setFunction(self::FUNCTION_WP_STATICIZE_EMOJI);
            $unregistered_filter_listeners[] = RemoveHookDTO::make('wp_mail')
                ->setFunction('wp_staticize_emoji_for_email');
        }

        return $unregistered_filter_listeners;
    }
}
