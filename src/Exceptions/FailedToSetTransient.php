<?php

namespace Wordless\Exceptions;

use DateTime;
use Exception;
use Throwable;

class FailedToSetTransient extends Exception
{
    /** @var mixed $transient_data */
    private $transient_data;
    /** @var mixed $transient_expiration */
    private $transient_expiration;
    private string $transient_key;

    public function __construct(
        string    $transient_key,
                  $transient_data,
                  $transient_expiration,
        Throwable $previous = null
    )
    {
        $this->transient_key = $transient_key;
        $this->transient_data = $transient_data;
        $this->transient_expiration = $transient_expiration;

        parent::__construct(
            "Failed to add a transient with key $this->transient_key, expiration {$this->getTransientExpiration(true)} and data: {$this->getTransientData(true)}",
            0,
            $previous
        );
    }

    public function getTransientData(bool $as_string = false)
    {
        if ($as_string) {
            return var_export($this->transient_data, true);
        }

        return $this->transient_data;
    }

    public function getTransientExpiration(bool $as_string = false)
    {
        if (!$as_string) {
            return $this->transient_expiration;
        }

        if ($this->transient_expiration instanceof DateTime) {
            return $this->transient_expiration->format('r');
        }

        return "$this->transient_expiration";
    }

    public function getTransientKey(): string
    {
        return $this->transient_key;
    }
}
