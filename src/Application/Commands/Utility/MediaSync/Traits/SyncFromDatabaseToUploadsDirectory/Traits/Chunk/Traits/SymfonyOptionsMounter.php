<?php

namespace App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Traits;

use Symfony\Component\Console\Input\InputOption;

trait SymfonyOptionsMounter
{
    private function mountChunkOption(): array
    {
        return [
            self::OPTION_NAME_FIELD => self::OPTION_NAME_CHUNK,
            self::OPTION_SHORTCUT_FIELD => 'c',
            self::OPTION_MODE_FIELD => InputOption::VALUE_OPTIONAL,
            self::OPTION_DEFAULT_FIELD => false,
            self::OPTION_DESCRIPTION_FIELD =>
                'Asks to continue after the given number of uploads files are processed.',
        ];
    }

    private function mountChunkOptions(): array
    {
        return [
            $this->mountChunkOption(),
            $this->mountOnceOption(),
        ];
    }

    private function mountOnceOption(): array
    {
        return [
            self::OPTION_NAME_FIELD => self::OPTION_NAME_ONCE,
            self::OPTION_SHORTCUT_FIELD => 'o',
            self::OPTION_MODE_FIELD => InputOption::VALUE_NONE,
            self::OPTION_DESCRIPTION_FIELD => 'Avoids continue question by processing only one chunk.',
        ];
    }
}
