<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Role\Traits\Repository\Traits;

use Wordless\Wordpress\Models\Role;
use Wordless\Wordpress\Models\Role\Dictionary;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToFindRole;

trait FromDictionary
{
    private static Dictionary $dictionary;

    /**
     * @return Role[]
     */
    public static function all(): array
    {
        return self::getDictionary()->all();
    }

    public static function allAsArray(): array
    {
        return self::getDictionary()->allAsArray();
    }

    /**
     * @return string[]
     */
    public static function allNames(): array
    {
        return self::getDictionary()->allNames();
    }

    public static function get(string $role): ?static
    {
        try {
            return static::getOrFail($role);
        } catch (FailedToFindRole) {
            return null;
        }
    }

    /**
     * @param string $role
     * @return static
     * @throws FailedToFindRole
     */
    public static function getOrFail(string $role): static
    {
        if ($roleObject = self::getDictionary()->get($role)) {
            return new static($roleObject);
        }

        throw new FailedToFindRole($role);
    }

    private static function getDictionary(): Dictionary
    {
        return self::$dictionary ?? self::$dictionary = Dictionary::getInstance();
    }
}
