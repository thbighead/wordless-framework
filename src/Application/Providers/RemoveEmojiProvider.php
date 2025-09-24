<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Listeners\RemoveEmojiFromTinyMce;
use Wordless\Application\Listeners\RemoveEmojiFromWpResourceHints;
use Wordless\Exceptions\FailedToRetrieveConfigFromWordpressConfigFile;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO\Exceptions\TriedToSetFunctionWhenRemovingListener;
use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Wordpress\Hook\Enums\Action;
use Wordless\Wordpress\Hook\Enums\Filter;

class RemoveEmojiProvider extends Provider
{
    final public const CONFIG_KEY_REMOVE_WP_EMOJIS = 'remove_wp_emojis';
    private const FUNCTION_PRINT_EMOJI_DETECTION_SCRIPT = 'print_emoji_detection_script';
    private const FUNCTION_PRINT_EMOJI_STYLES = 'print_emoji_styles';
    private const FUNCTION_WP_STATICIZE_EMOJI = 'wp_staticize_emoji';

    /**
     * @return string[]|Listener[]
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
     */
    public function registerListeners(): array
    {
        if (!$this->isApplicationConfiguredToRemoveEmojis()) {
            return [];
        }

        return [
            RemoveEmojiFromTinyMce::class,
            RemoveEmojiFromWpResourceHints::class,
        ];
    }

    /**
     * @return RemoveHookDTO[]
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
     * @throws TriedToSetFunctionWhenRemovingListener
     */
    public function unregisterActionListeners(): array
    {
        if (!$this->isApplicationConfiguredToRemoveEmojis()) {
            return [];
        }

        return [
            RemoveHookDTO::make(Action::wp_head)->setFunction(
                self::FUNCTION_PRINT_EMOJI_DETECTION_SCRIPT,
                7
            ),
            RemoveHookDTO::make(Action::admin_print_scripts)
                ->setFunction(self::FUNCTION_PRINT_EMOJI_DETECTION_SCRIPT),
            RemoveHookDTO::make(Action::admin_print_styles)
                ->setFunction(self::FUNCTION_PRINT_EMOJI_STYLES),
            RemoveHookDTO::make(Action::wp_print_styles)
                ->setFunction(self::FUNCTION_PRINT_EMOJI_STYLES),
        ];
    }

    /**
     * @return RemoveHookDTO[]
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
     * @throws TriedToSetFunctionWhenRemovingListener
     */
    public function unregisterFilterListeners(): array
    {
        if (!$this->isApplicationConfiguredToRemoveEmojis()) {
            return [];
        }
        return [
            RemoveHookDTO::make(Filter::the_content_feed)
                ->setFunction(self::FUNCTION_WP_STATICIZE_EMOJI),
            RemoveHookDTO::make(Filter::comment_text_rss)
                ->setFunction(self::FUNCTION_WP_STATICIZE_EMOJI),
            RemoveHookDTO::make(Filter::wp_mail)
                ->setFunction('wp_staticize_emoji_for_email'),
        ];
    }

    /**
     * @return bool
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
     */
    private function isApplicationConfiguredToRemoveEmojis(): bool
    {
        return Config::wordpressAdmin(self::CONFIG_KEY_REMOVE_WP_EMOJIS, false);
    }
}
