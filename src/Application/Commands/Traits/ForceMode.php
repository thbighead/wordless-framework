<?php

namespace Wordless\Application\Commands\Traits;

use Wordless\Application\Commands\Traits\ForceMode\DTO\ForceModeOptionDTO;

trait ForceMode
{
    protected function isForceMode(): bool
    {
        return (bool)$this->input->getOption(ForceModeOptionDTO::FORCE_MODE);
    }

    protected function mountForceModeOption(string $option_description): ForceModeOptionDTO
    {
        return new ForceModeOptionDTO($option_description);
    }
}
