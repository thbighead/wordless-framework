<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Traits;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Traits\ForceMode\DTO\ForceModeOptionDTO;

trait ForceMode
{
    /**
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function isForceMode(): bool
    {
        return (bool)$this->input->getOption(ForceModeOptionDTO::FORCE_MODE);
    }

    protected function mountForceModeOption(string $option_description): ForceModeOptionDTO
    {
        return new ForceModeOptionDTO($option_description);
    }
}
