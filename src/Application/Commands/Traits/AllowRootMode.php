<?php

namespace Wordless\Application\Commands\Traits;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Traits\AllowRootMode\DTO\AllowRootModeOptionDTO;

trait AllowRootMode
{
    /**
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function isAllowRootMode(): bool
    {
        return (bool)$this->input->getOption(AllowRootModeOptionDTO::ALLOW_ROOT_MODE);
    }

    protected function mountAllowRootModeOption(
        string $option_description = 'Runs every WP-CLI using --allow-root flag'
    ): AllowRootModeOptionDTO
    {
        return new AllowRootModeOptionDTO($option_description);
    }
}
