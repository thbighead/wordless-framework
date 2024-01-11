<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DataCache\Traits;

use DateTimeInterface;
use Wordless\Application\Helpers\DataCache\Exceptions\FailedToSetTransient;
use Wordless\Application\Helpers\DataCache\Exceptions\InvalidTransientExpirationValue;
use Wordless\Application\Helpers\GetType;

trait Internal
{
    // https://developer.wordpress.org/reference/functions/get_transient/#more-information
    private const MAX_TRANSIENT_KEY_SIZE = 172;

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
     * @throws FailedToSetTransient
     */
    private static function setTransient(string $key, mixed $value, int $expiration): void
    {
        if (!set_transient($key, $value, $expiration)) {
            throw new FailedToSetTransient($key, $value, $expiration);
        }
    }
}
