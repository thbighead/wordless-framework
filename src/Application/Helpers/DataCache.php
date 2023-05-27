<?php

namespace Wordless\Application\Helpers;

use DateTimeInterface;
use Wordless\Application\Helpers\DataCache\Exceptions\FailedToSetTransient;
use Wordless\Application\Helpers\DataCache\Exceptions\InvalidTransientExpirationValue;
use Wordless\Application\Helpers\DataCache\Exceptions\TransientKeyIsTooLong;
use Wordless\Application\Helpers\DataCache\Exceptions\TransientKeyNotFound;

class DataCache
{
    // https://developer.wordpress.org/reference/functions/get_transient/#more-information
    private const MAX_TRANSIENT_KEY_SIZE = 172;

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

    /**
     * @param string $key
     * @param DateTimeInterface|int|string $expiration
     * @return int only positive values
     * @throws InvalidTransientExpirationValue
     */
    private static function prepareTransientExpirationValue(string $key, DateTimeInterface|int|string $expiration): int
    {
        switch (GetType::of($expiration)) {
            case GetType::INTEGER:
                $expiration_as_int = $expiration;
                break;
            case GetType::STRING:
                if (($transformed_expiration = strtotime($expiration)) !== false) {
                    $expiration_as_int = $transformed_expiration - time();
                    break;
                }

                if (is_numeric($expiration)) {
                    $expiration_as_int = (int)$expiration;
                    break;
                }

                throw new InvalidTransientExpirationValue($key, $expiration);
            case DateTimeInterface::class:
                /** @var DateTimeInterface $expiration */
                $expiration_as_int = $expiration->getTimestamp() - time();
                break;
            default:
                throw new InvalidTransientExpirationValue($key, $expiration);
        }

        return abs($expiration_as_int);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $expiration
     * @return void
     */
    private static function setTransient(string $key, mixed $value, int $expiration): void
    {
        if (!set_transient($key, $value, $expiration)) {
            throw new FailedToSetTransient($key, $value, $expiration);
        }
    }
}
