<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DataCache\Exceptions;

use DateTimeInterface;
use InvalidArgumentException;
use Throwable;
use Wordless\Application\Helpers\GetType;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidTransientExpirationValue extends InvalidArgumentException
{
    public function __construct(
        private readonly string $key,
        private readonly mixed  $expiration_value,
        ?Throwable              $previous = null
    )
    {
        parent::__construct(
            "Failed to set transient with key $this->key and expiration with type {$this->getExpirationValueType()} with the fallowing data: {$this->getExpirationValue(true)}",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }

    public function getExpirationValue(bool $as_string = false): mixed
    {
        if (!$as_string) {
            return $this->expiration_value;
        }

        if ($this->expiration_value instanceof DateTimeInterface) {
            return $this->expiration_value->format('r');
        }

        if (GetType::isStringable($this->expiration_value)) {
            return "$this->expiration_value";
        }

        return var_export($this->expiration_value, true);
    }

    public function getExpirationValueType(): string
    {
        return GetType::of($this->expiration_value);
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
