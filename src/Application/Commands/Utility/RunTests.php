<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility;

use Symfony\Component\Console\Exception\InvalidArgumentException as SymfonyConsoleInvalidArgumentException;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\Exceptions\FailedToRunCommand;
use Wordless\Application\Commands\Utility\RunTests\Traits\CoverageOption;
use Wordless\Application\Commands\Utility\RunTests\Traits\FilterOption;
use Wordless\Application\Commands\Utility\RunTests\Traits\OutputOption;
use Wordless\Application\Commands\Utility\RunTests\Traits\PathArgument;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\External\Exceptions\CallExternalCommandException;

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
     * @throws FailedToRunCommand
     */
    protected function runIt(): int
    {
        try {
            $phpunit_command = "vendor/bin/phpunit {$this->getTestOutputFormat()}";

            return $this->resolveFilterOptions($phpunit_command)
                ->resolveCoverageOptions($phpunit_command)
                ->callExternalCommand("$phpunit_command {$this->getPathArgument()}")
                ->result_code;
        } catch (CallExternalCommandException|CliReturnedNonZero|SymfonyConsoleInvalidArgumentException $exception) {
            throw new FailedToRunCommand(static::COMMAND_NAME, $exception);
        }
    }
}
