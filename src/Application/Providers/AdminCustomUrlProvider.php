<?php declare(strict_types=1);

namespace Wordless\Application\Providers;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Listeners\RedirectAdminUris;
use Wordless\Infrastructure\Provider;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO;
use Wordless\Infrastructure\Provider\DTO\RemoveHookDTO\Exceptions\TriedToSetFunctionWhenRemovingListener;
use Wordless\Infrastructure\Wordpress\Listener;
use Wordless\Wordpress\Hook\Enums\Action;

class AdminCustomUrlProvider extends Provider
{
    final public const CONFIG_KEY_CUSTOM_ADMIN_URI = 'custom_admin_uri';
    private const ADMIN_DEFAULT_URI = 'wp-core';

    /**
     * @param bool $wrapped
     * @return string
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    public static function getCustomUri(bool $wrapped = true): string
    {
        $custom_admin_uri = (string)Config::wordpressAdmin(
            self::CONFIG_KEY_CUSTOM_ADMIN_URI,
            self::ADMIN_DEFAULT_URI
        );

        if (empty($custom_admin_uri)) {
            $custom_admin_uri = self::ADMIN_DEFAULT_URI;
        }

        return $wrapped ? Str::wrap($custom_admin_uri) : trim($custom_admin_uri, '/');
    }

    /**
     * @return string[]|Listener[]
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    public function registerListeners(): array
    {
        if (!$this->hasCustomAdminUrlConfigured()) {
            return [];
        }

        return [
            RedirectAdminUris::class,
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
        if (!$this->hasCustomAdminUrlConfigured()) {
            return [];
        }

        return [
            RemoveHookDTO::make(Action::template_redirect)
                ->setFunction('wp_redirect_admin_locations', 1000),
        ];
    }

    /**
     * @return bool
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    private function hasCustomAdminUrlConfigured(): bool
    {
        return !empty((string)Config::wordpressAdmin(self::CONFIG_KEY_CUSTOM_ADMIN_URI));
    }
}
