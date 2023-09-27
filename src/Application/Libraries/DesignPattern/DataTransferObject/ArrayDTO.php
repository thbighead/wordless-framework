<?php

namespace Wordless\Application\Libraries\DesignPattern\DataTransferObject;

abstract class ArrayDTO
{
    protected ?array $data = null;

    public function getData(): ?array
    {
        return $this->data;
    }

    public static function make(): static
    {
        return new static;
    }

    private function __construct()
    {
    }
}
