<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use DateTimeInterface;
use Wordless\Application\Helpers\DataCache\Exceptions\FailedToSetTransient;
use Wordless\Application\Helpers\DataCache\Exceptions\InvalidTransientExpirationValue;
use Wordless\Application\Helpers\DataCache\Exceptions\TransientKeyIsTooLong;
use Wordless\Application\Helpers\DataCache\Exceptions\TransientKeyNotFound;
use Wordless\Application\Helpers\DataCache\Traits\Internal;

class DataCache
{
    use Internal;

    /**
     * @param string $key
     * @return void
     * @throws TransientKeyNotFound
     */
    public static function delete(string $key): void
    {
        if (delete_transient($key) === false) {
            throw new TransientKeyNotFound($key);
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $transient_result = get_transient($key);

        return $transient_result === false ? $default : $transient_result;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param DateTimeInterface|int|string $expires_in
     * @return void
     * @throws FailedToSetTransient
     * @throws InvalidTransientExpirationValue
     * @throws TransientKeyIsTooLong
     */
    public static function set(string $key, mixed $value, DateTimeInterface|int|string $expires_in = 0): void
    {
        if (strlen($key) > self::MAX_TRANSIENT_KEY_SIZE) {
            throw new TransientKeyIsTooLong($key);
        }

        self::setTransient($key, $value, self::prepareTransientExpirationValue($key, $expires_in));
    }
}
