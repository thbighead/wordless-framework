<?php

namespace Wordless\Application\Commands\Traits;

use Symfony\Component\Console\Input\InputOption;

trait AllowRootMode
{
    protected function allowRootMode(): bool
    {
        return (bool)$this->input->getOption(self::ALLOW_ROOT_MODE);
    }

    protected function mountAllowRootModeOption(
        string $option_description = 'Runs every WP-CLI using --allow-root flag'
    ): array
    {
        return [
            self::OPTION_NAME_FIELD => self::ALLOW_ROOT_MODE,
            self::OPTION_MODE_FIELD => InputOption::VALUE_NONE,
            self::OPTION_DESCRIPTION_FIELD => $option_description,
        ];
    }
}
