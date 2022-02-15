<?php

namespace Wordless\Contracts\Command;

trait AllowRootMode
{
    protected function allowRootMode(): bool
    {
        return (bool)$this->input->getOption(self::ALLOW_ROOT_MODE);
    }
}