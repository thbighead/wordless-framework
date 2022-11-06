<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Adapters\WordlessCommand;
use Wordless\Contracts\Command\LoadWpConfig;
use Wordless\Exceptions\FailedToCopyStub;
use Wordless\Exceptions\PathNotFoundException;

class MigrationList extends WordlessCommand
{
    use LoadWpConfig;

    protected static $defaultName = 'migrate:list';

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Lists all migrations already executed.';
    }

    protected function help(): string
    {
        return $this->description();
    }

    protected function options(): array
    {
        return [];
    }

    /**
     * @return int
     */
    protected function runIt(): int
    {
        dump(get_option(Migrate::MIGRATIONS_WP_OPTION_NAME));

        return Command::SUCCESS;
    }
}
