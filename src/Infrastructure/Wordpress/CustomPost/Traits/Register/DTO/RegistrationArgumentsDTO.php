<?php

namespace Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\DTO;

use Wordless\Contracts\ArrayDTO;

class RegistrationArgumentsDTO extends ArrayDTO
{
    private array $arguments = [];

    public function getData(): array
    {
        return $this->arguments;
    }
}
