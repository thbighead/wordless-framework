<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Wordless\Application\Cachers\PluginsCacher;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToFindCachedKey;
use Wordless\Application\Helpers\Plugin\Contracts\Subjectable;
use Wordless\Core\InternalCache;
use Wordless\Core\InternalCache\Exceptions\InternalCacheNotLoaded;

class Plugin extends Subjectable
{
    final public const DATA_ID = 'ID';
    final public const DATA_IS_ACTIVE = 'IsActive';
    final public const TYPE_MUST_USE = 'must_use';
    final public const TYPE_NORMAL = 'normal';

    public static function all(): array
    {
        return array_merge(static::allNormal(), static::allMustUse());
    }

    public static function allMustUse(): array
    {
        try {
            return InternalCache::getValueOrFail('plugins.' . self::TYPE_MUST_USE);
        } catch (FailedToFindCachedKey|InternalCacheNotLoaded) {
            return PluginsCacher::retrievePluginsList()[self::TYPE_MUST_USE] ?? [];
        }
    }

    public static function allNormal(): array
    {
        try {
            return InternalCache::getValueOrFail('plugins.' . self::TYPE_NORMAL);
        } catch (FailedToFindCachedKey|InternalCacheNotLoaded) {
            return PluginsCacher::retrievePluginsList()[self::TYPE_NORMAL] ?? [];
        }
    }

    public static function data(string $plugin): array
    {
        return ($plugins = static::all())[$plugin]
            ?? $plugins["$plugin.php"]
            ?? $plugins["$plugin/$plugin.php"]
            ?? [];
    }

    public static function isActive(string $plugin): bool
    {
        return static::isMustUse($plugin) || static::data($plugin)[];
    }

    public static function isMustUse(string $plugin): bool
    {
        return !empty(($must_use_plugins = static::allMustUse())[$plugin]
            ?? $must_use_plugins["$plugin.php"]
            ?? $must_use_plugins["$plugin/$plugin.php"]
            ?? false);
    }
}
