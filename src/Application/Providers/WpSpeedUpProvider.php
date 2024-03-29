<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Listeners\RemoveAdditionalCssFromAdmin;
use Wordless\Application\Listeners\RemoveGlobalCustomInlineStyles;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO\Exceptions\TriedToSetFunctionWhenRemovingListener;
use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Wordpress\Hook\Enums\Action;

class WpSpeedUpProvider extends Provider
{
    final public const CONFIG_KEY_SPEED_UP_WP = 'speed_up_wp';

    /**
     * @return string[]|Listener[]
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    public function registerListeners(): array
    {
        if (!$this->isConfiguredToSpeedUpWordpress()) {
            return [];
        }

        return [
            RemoveAdditionalCssFromAdmin::class,
            RemoveGlobalCustomInlineStyles::class,
        ];
    }

    /**
     * @return string[]|Provider[]
     */
    public function registerProviders(): array
    {
        return [
            RemoveEmojiProvider::class,
        ];
    }

    /**
     * @return RemoveHookDTO[]
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     * @throws TriedToSetFunctionWhenRemovingListener
     */
    public function unregisterActionListeners(): array
    {
        if (!$this->isConfiguredToSpeedUpWordpress()) {
            return [];
        }

        return [
            RemoveHookDTO::make(Action::wp_head)
                ->setFunction('wp_generator')
                ->setFunction('wp_shortlink_wp_head')
                ->setFunction('feed_links', 2)
                ->setFunction('feed_links_extra', 3),
        ];
    }

    /**
     * @return bool
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    private function isConfiguredToSpeedUpWordpress(): bool
    {
        return Config::wordpressAdmin(self::CONFIG_KEY_SPEED_UP_WP, false);
    }
}
