<?php

namespace Wordless\Contracts\DTO\Traits;

trait EmptyMaked
{
    public static function make(): static
    {
        return new self;
    }

    private function __construct()
    {
    }
}
