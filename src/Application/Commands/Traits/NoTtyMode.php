<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Traits;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Traits\NoTtyMode\DTO\NoTtyModeOptionDTO;

trait NoTtyMode
{
    /**
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function isNoTtyMode(): bool
    {
        return (bool)$this->input->getOption(NoTtyModeOptionDTO::NO_TTY_MODE);
    }

    protected function mountNoTtyOption(
        string $description = 'Runs external command symfony processes with set tty as false.'
    ): NoTtyModeOptionDTO
    {
        return new NoTtyModeOptionDTO($description);
    }
}
