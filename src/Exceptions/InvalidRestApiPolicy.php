<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class InvalidRestApiPolicy extends Exception
{
    /**
     * @var mixed
     */
    private $policy;

    public function __construct($permission, ?Throwable $previous = null)
    {
        $this->policy = $permission;

        parent::__construct('Invalid rest-api.endpoints.policy', 0, $previous);
    }

    public function getPolicy()
    {
        return $this->policy;
    }
}
