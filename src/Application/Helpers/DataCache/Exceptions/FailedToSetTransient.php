<?php

namespace Wordless\Application\Helpers\DataCache\Exceptions;

use DateTimeInterface;
use InvalidArgumentException;
use Throwable;
use Wordless\Application\Helpers\GetType;
use Wordless\Enums\ExceptionCode;

class FailedToSetTransient extends InvalidArgumentException
{
    public function __construct(
        private readonly string $transient_key,
        private readonly mixed  $transient_data,
        private readonly mixed  $transient_expiration,
        ?Throwable              $previous = null
    )
    {
        parent::__construct(
            "Failed to add a transient with key $this->transient_key, expiration {$this->getTransientExpiration(true)} and data: {$this->getTransientData(true)}",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }

    public function getTransientData(bool $as_string = false): mixed
    {
        if ($as_string && !GetType::isStringable($this->transient_data)) {
            return var_export($this->transient_data, true);
        }

        return $this->transient_data;
    }

    public function getTransientExpiration(bool $as_string = false): mixed
    {
        if (!$as_string) {
            return $this->transient_expiration;
        }

        if ($this->transient_expiration instanceof DateTimeInterface) {
            return $this->transient_expiration->format('r');
        }

        if (GetType::isStringable($this->transient_expiration)) {
            return "$this->transient_expiration";
        }

        return var_export($this->transient_expiration, true);
    }

    public function getTransientKey(): string
    {
        return $this->transient_key;
    }
}
