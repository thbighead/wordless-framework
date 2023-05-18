<?php

namespace Wordless\Application\Commands\Traits;

use Symfony\Component\Console\Input\InputOption;

trait ForceMode
{
    protected function isForceMode(): bool
    {
        return (bool)$this->input->getOption(self::FORCE_MODE);
    }

    protected function mountForceModeOption(string $option_description): array
    {
        return [
            self::OPTION_NAME_FIELD => self::FORCE_MODE,
            self::OPTION_SHORTCUT_FIELD => 'f',
            self::OPTION_MODE_FIELD => InputOption::VALUE_NONE,
            self::OPTION_DESCRIPTION_FIELD => $option_description,
        ];
    }
}
