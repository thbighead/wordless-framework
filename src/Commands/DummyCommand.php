<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Abstractions\Guessers\WordlessFrameworkVersionGuesser;
use Wordless\Adapters\WordlessCommand;

class DummyCommand extends WordlessCommand
{
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
        dump((new WordlessFrameworkVersionGuesser)->getValue());

        return Command::SUCCESS;
    }
}