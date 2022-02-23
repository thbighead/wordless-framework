<?php

namespace Wordless\Helpers;

use DateTime;
use Wordless\Exceptions\FailedToSetTransient;
use Wordless\Exceptions\InvalidTransientExpirationValue;
use Wordless\Exceptions\TransientKeyIsTooLong;

class DataCache
{
    // https://developer.wordpress.org/reference/functions/get_transient/#more-information
    private const MAX_TRANSIENT_KEY_SIZE = 172;

    public static function get(string $key, $default = null)
    {
        $transient_result = get_transient($key);

        return $transient_result === false ? $default : $transient_result;
    }

    /**
     * @param string $key
     * @param $value
     * @param int|string|DateTime $expires_in
     * @return void
     * @throws FailedToSetTransient
     * @throws InvalidTransientExpirationValue
     * @throws TransientKeyIsTooLong
     */
    public static function set(string $key, $value, $expires_in = 0)
    {
        if (strlen($key) > self::MAX_TRANSIENT_KEY_SIZE) {
            throw new TransientKeyIsTooLong($key);
        }

        self::setTransient($key, $value, self::prepareTransientExpirationValue($key, $expires_in), $expires_in);
    }

    /**
     * If for some reason a negative int would be prepared, a zero is chosen instead
     * (which leads to a cache that does not expire).
     *
     * @param string $key
     * @param $expiration
     * @return int
     * @throws InvalidTransientExpirationValue
     */
    private static function prepareTransientExpirationValue(string $key, $expiration): int
    {
        switch (GetType::of($expiration)) {
            case GetType::INTEGER:
                $expiration_as_int = $expiration;
                break;
            case GetType::STRING:
                $expiration_as_int = ($transformed_expiration = strtotime($expiration)) === false ?
                    (int)$expiration : $transformed_expiration - time();
                break;
            case DateTime::class:
                /** @var DateTime $expiration */
                $expiration_as_int = $expiration->getTimestamp() - time();
                break;
            default:
                throw new InvalidTransientExpirationValue($key, $expiration);
        }

        return max($expiration_as_int, 0);
    }

    /**
     * @param string $key
     * @param $value
     * @param int $expiration
     * @param $original_expires_in
     * @return void
     * @throws FailedToSetTransient
     */
    private static function setTransient(string $key, $value, int $expiration, $original_expires_in)
    {
        if (set_transient($key, $value, $expiration)) {
            throw new FailedToSetTransient($key, $value, $original_expires_in ?? $expiration);
        }
    }
}
