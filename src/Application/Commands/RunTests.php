<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Exception\InvalidArgumentException as SymfonyConsoleInvalidArgumentException;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\RunTests\Traits\CoverageOption;
use Wordless\Application\Commands\RunTests\Traits\FilterOption;
use Wordless\Application\Commands\RunTests\Traits\OutputOption;
use Wordless\Application\Commands\RunTests\Traits\PathArgument;
use Wordless\Infrastructure\ConsoleCommand;

class RunTests extends ConsoleCommand
{
    use CoverageOption;
    use FilterOption;
    use OutputOption;
    use PathArgument;

    final public const COMMAND_NAME = 'test';

    /**
     * @inheritDoc
     */
    protected function arguments(): array
    {
        return [$this->mountPathArgument()];
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
            $this->mountCoverageOption(),
            $this->mountFilterOption(),
            $this->mountOutputOption(),
        ];
    }

    /**
     * @return int
     * @throws CliReturnedNonZero
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws SymfonyConsoleInvalidArgumentException
     */
    protected function runIt(): int
    {
        $phpunit_command = "vendor/bin/phpunit {$this->getTestOutputFormat()}";

        return $this->resolveFilterOptions($phpunit_command)
            ->resolveCoverageOptions($phpunit_command)
            ->callExternalCommand("$phpunit_command {$this->getPathArgument()}")
            ->result_code;
    }
}
