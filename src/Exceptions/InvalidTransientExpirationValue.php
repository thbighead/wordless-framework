<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use Wordless\Application\Helpers\GetType;

class InvalidTransientExpirationValue extends Exception
{
    /** @var mixed $expiration_value */
    private $expiration_value;

    public function __construct(string $key, $expiration_value, Throwable $previous = null)
    {
        $this->expiration_value = $expiration_value;

        parent::__construct(
            "Failed to set transient with key $key and expiration with type {$this->getExpirationValueType()}",
            0,
            $previous
        );
    }

    /**
     * @return mixed
     */
    public function getExpirationValue()
    {
        return $this->expiration_value;
    }

    public function getExpirationValueType(): string
    {
        return GetType::of($this->expiration_value);
    }
}
