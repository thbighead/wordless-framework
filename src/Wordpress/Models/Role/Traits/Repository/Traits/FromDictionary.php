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

    public static function find(string $role): ?static
    {
        try {
            return static::findOrFail($role);
        } catch (FailedToFindRole) {
            return null;
        }
    }

    /**
     * @param string $role
     * @return static
     * @throws FailedToFindRole
     */
    public static function findOrFail(string $role): static
    {
        if ($roleObject = self::getDictionary()->find($role)) {
            return new static($roleObject);
        }

        throw new FailedToFindRole($role);
    }

    private static function getDictionary(): Dictionary
    {
        return self::$dictionary ?? self::$dictionary = Dictionary::getInstance();
    }
}
