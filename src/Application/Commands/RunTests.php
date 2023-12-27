<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Exception\InvalidArgumentException as SymfonyConsoleInvalidArgumentException;
use Symfony\Component\Process\Exception\InvalidArgumentException as SymfonyProcessInvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\RunTests\Traits\FilterOption;
use Wordless\Application\Commands\RunTests\Traits\OutputOption;
use Wordless\Infrastructure\ConsoleCommand;

class RunTests extends ConsoleCommand
{
    use FilterOption;
    use OutputOption;

    final public const COMMAND_NAME = 'test';

    protected static $defaultName = self::COMMAND_NAME;

    /**
     * @inheritDoc
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Runs tests.';
    }

    protected function help(): string
    {
        return 'Checkout the command options for more.';
    }

    /**
     * @inheritDoc
     */
    protected function options(): array
    {
        return [
            $this->mountFilterOption(),
            $this->mountOutputOption(),
        ];
    }

    /**
     * @return int
     * @throws CliReturnedNonZero
     * @throws LogicException
     * @throws SymfonyConsoleInvalidArgumentException
     * @throws SymfonyProcessInvalidArgumentException
     */
    protected function runIt(): int
    {
        $phpunit_command = 'vendor/bin/phpunit';
        $phpunit_command = "$phpunit_command {$this->getTestOutputFormat()}";
        $filter_string = $this->getFilterOption();

        if (!empty($filter_string)) {
            $phpunit_command = "$phpunit_command $filter_string";
        }

        $this->callExternalCommand($phpunit_command);

        return self::SUCCESS;
    }
}
