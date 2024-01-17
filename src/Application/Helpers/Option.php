<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Option\Exception\FailedToCreateOption;
use Wordless\Application\Helpers\Option\Exception\FailedToFindOption;
use Wordless\Application\Helpers\Option\Exception\FailedToUpdateOption;

class Option
{
    /**
     * @param string $option_key
     * @param mixed $option_value
     * @param bool $autoload
     * @return void
     * @throws FailedToCreateOption
     */
    public static function create(string $option_key, mixed $option_value, bool $autoload = true): void
    {
        if (!add_option($option_key, $option_value, autoload: $autoload)) {
            throw new FailedToCreateOption($option_key, $option_value, $autoload);
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
     * @throws FailedToUpdateOption
     */
    public static function update(string $option_key, mixed $option_value, ?bool $autoload = null): bool
    {
        try {
            if (static::getOrFail($option_key) === $option_value) {
                return false;
            }
        } catch (FailedToFindOption) {
        } finally {
            if (!update_option($option_key, $option_value, $autoload)) {
                throw new FailedToUpdateOption($option_key, $option_value, $autoload);
            }
        }

        return true;
    }
}
