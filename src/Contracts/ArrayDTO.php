<?php

namespace Wordless\Contracts;

abstract class ArrayDTO
{
    protected ?array $data = null;

    abstract public function getData(): ?array;

    public static function make(): static
    {
        return new static;
    }

    private function __construct()
    {
    }
}
