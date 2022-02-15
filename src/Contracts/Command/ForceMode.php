<?php

namespace Wordless\Contracts\Command;

trait ForceMode
{
    protected function isForceMode(): bool
    {
        return (bool)$this->input->getOption(self::FORCE_MODE);
    }
}