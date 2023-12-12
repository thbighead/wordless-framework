<?php

namespace Wordless\Application\Commands\Migrate\Traits;

trait ExecutionTimestamp
{
    private string $now;

    private function getNow(): string
    {
        return $this->now ?? $this->now = date('Y-m-d H:i:s');
    }
}
