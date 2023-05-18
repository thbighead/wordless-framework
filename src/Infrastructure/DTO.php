<?php

namespace Wordless\Infrastructure;

abstract class DTO
{
    protected mixed $data = null;

    abstract public function getData(): mixed;
}
