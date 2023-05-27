<?php

namespace Wordless\Contracts;

abstract class ArrayDTO
{
    protected ?array $data = null;

    abstract public function getData(): ?array;
}
