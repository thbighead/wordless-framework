<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Schedules;

use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Commands\Traits\NoTtyMode\DTO\NoTtyModeOptionDTO;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Commands\WpCliCaller;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Libraries\Carbon\Carbon;
use Wordless\Infrastructure\ConsoleCommand;

class RunSchedules extends ConsoleCommand
{
    use LoadWpConfig;
    use RunWpCliCommand;

    final public const COMMAND_NAME = 'schedule:run';

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Runs all cron jobs registered.';
    }


    protected function help(): string
    {
        return 'Checks for Wordpress cron jobs to run throughout WP-CLI command.';
    }

    protected function options(): array
    {
        return [
            $this->mountAllowRootModeOption()
        ];
    }

    protected function runIt(): int
    {
        $datetime = Carbon::now()->format('Y-m-d H:i:s e');

        try {
            $result = $this->callConsoleCommandSilentlyWithoutInterrupt(WpCliCaller::COMMAND_NAME, [
                WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => 'cron event run --due-now',
                '--' . NoTtyModeOptionDTO::NO_TTY_MODE => true,
            ]);

            if ($result->output) {
                $this->write(Str::finishWith(
                    "[$datetime] Result code: $result->result_code > $result->output",
                    PHP_EOL
                ));
            }
        } catch (CommandNotFoundException|ExceptionInterface $exception) {
            $this->writelnDanger("[$datetime] {$exception->getMessage()}");
            $this->writeln($exception->getTraceAsString());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
