<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Option\Contracts\Subjectable;
use Wordless\Application\Helpers\Option\Exception\FailedToCreateOption;
use Wordless\Application\Helpers\Option\Exception\FailedToDeleteOption;
use Wordless\Application\Helpers\Option\Exception\FailedToFindOption;
use Wordless\Application\Helpers\Option\Exception\FailedToUpdateOption;

class Option extends Subjectable
{
    public static function create(string $option_key, mixed $option_value, bool $autoload = true): bool
    {
        try {
            static::createOrFail($option_key, $option_value, $autoload);

            return true;
        } catch (FailedToCreateOption) {
            return false;
        }
    }

    /**
     * @param string $option_key
     * @param mixed $option_value
     * @param bool $autoload
     * @return void
     * @throws FailedToCreateOption
     */
    public static function createOrFail(string $option_key, mixed $option_value, bool $autoload = true): void
    {
        if (!add_option($option_key, $option_value, autoload: $autoload)) {
            throw new FailedToCreateOption($option_key, $option_value, $autoload);
        }
    }

    public static function createOrUpdate(string $option_key, mixed $option_value, ?bool $autoload = null): bool
    {
        try {
            static::createUpdateOrFail($option_key, $option_value, $autoload ?? true);

            return true;
        } catch (FailedToUpdateOption) {
            return false;
        }
    }

    /**
     * @param string $option_key
     * @param mixed $option_value
     * @param bool|null $autoload
     * @return void
     * @throws FailedToUpdateOption
     */
    public static function createUpdateOrFail(string $option_key, mixed $option_value, ?bool $autoload = null): void
    {
        try {
            static::createOrFail($option_key, $option_value, $autoload ?? true);
        } catch (FailedToCreateOption) {
            static::updateOrFail($option_key, $option_value, $autoload);
        }
    }

    public static function delete(string $option_key): bool
    {
        try {
            static::deleteOrFail($option_key);

            return true;
        } catch (FailedToDeleteOption) {
            return false;
        }
    }

    /**
     * @param string $option_key
     * @return void
     * @throws FailedToDeleteOption
     */
    public static function deleteOrFail(string $option_key): void
    {
        if (!delete_option($option_key)) {
            throw new FailedToDeleteOption($option_key);
        }
    }

    public static function get(string $option_key, mixed $default = null): mixed
    {
        try {
            return self::getOrFail($option_key);
        } catch (FailedToFindOption) {
            return $default;
        }
    }

    /**
     * @param string $option_key
     * @return mixed
     * @throws FailedToFindOption
     */
    public static function getOrFail(string $option_key): mixed
    {
        $option = get_option($option_key, $exception = new FailedToFindOption($option_key));

        if ($option instanceof FailedToFindOption) {
            throw $exception;
        }

        return $option;
    }

    /**
     * @param string $option_key
     * @param mixed $option_value
     * @param bool|null $autoload
     * @return bool
     */
    public static function update(string $option_key, mixed $option_value, ?bool $autoload = null): bool
    {
        try {
            static::updateOrFail($option_key, $option_value, $autoload);

            return true;
        } catch (FailedToUpdateOption) {
            return false;
        }
    }

    /**
     * @param string $option_key
     * @param mixed $option_value
     * @param bool|null $autoload
     * @return void
     * @throws FailedToUpdateOption
     */
    public static function updateOrFail(string $option_key, mixed $option_value, ?bool $autoload = null): void
    {
        try {
            if (static::getOrFail($option_key) === $option_value) {
                return;
            }
        } catch (FailedToFindOption $exception) {
            throw new FailedToUpdateOption($option_key, $option_value, $autoload, $exception);
        }

        if (!update_option($option_key, $option_value, $autoload)) {
            throw new FailedToUpdateOption($option_key, $option_value, $autoload);
        }
    }
}
