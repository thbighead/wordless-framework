<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Adapters\Role;
use Wordless\Adapters\WordlessCommand;
use Wordless\Contracts\Command\LoadWpConfig;

class DummyCommand extends WordlessCommand
{
    use LoadWpConfig;

    protected static $defaultName = 'foo';

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Used for testing anything you want.';
    }

    protected function help(): string
    {
        return 'This is not sent to this package when installed through Composer.';
    }

    protected function options(): array
    {
        return [];
    }

    protected function runIt(): int
    {
        dump(Role::all(), Role::allAsArray(), Role::allNames());

        return Command::SUCCESS;
    }
}
