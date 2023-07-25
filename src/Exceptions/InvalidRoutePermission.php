<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;

class InvalidRoutePermission extends Exception
{
    /**
     * @var mixed
     */
    private $permission;
    private string $route;

    public function __construct(string $route, $permission, ?Throwable $previous = null)
    {
        $this->route = $route;
        $this->permission = $permission;

        parent::__construct("Invalid rest-api.endpoints.routes.$this->route", 0, $previous);
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function getRoute(): string
    {
        return $this->route;
    }
}
