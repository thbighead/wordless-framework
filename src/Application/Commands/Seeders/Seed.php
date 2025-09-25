<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\Internal\Exceptions\CallInternalCommandException;

class Seed extends ConsoleCommand
{
    final public const COMMAND_NAME = 'db:seed';

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Runs all seeders.';
    }

    protected function help(): string
    {
        return 'Call every single seeder command using default values.';
    }

    protected function options(): array
    {
        return [];
    }

    /**
     * @return int
     * @throws CallInternalCommandException
     * @throws CliReturnedNonZero
     */
    protected function runIt(): int
    {
        $this->callConsoleCommand(UsersSeeder::COMMAND_NAME);
        $this->callConsoleCommand(TaxonomyTermsSeeder::COMMAND_NAME);
        $this->callConsoleCommand(PostsSeeder::COMMAND_NAME);
        $this->callConsoleCommand(CommentsSeeder::COMMAND_NAME);

        return Command::SUCCESS;
    }
}
