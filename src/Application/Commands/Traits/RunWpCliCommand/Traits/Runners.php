<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Traits\RunWpCliCommand\Traits;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Exceptions\FailedToCallWpCliCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Exceptions\FailedToRunWpCliCommand;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Response;

trait Runners
{
    /**
     * @param string $wp_cli_command
     * @return Response
     * @throws FailedToRunWpCliCommand
     * @throws WpCliCommandReturnedNonZero
     */
    protected function runWpCliCommand(string $wp_cli_command): Response
    {
        try {
            return $this->resolveCommandModifiers($wp_cli_command)->callWpCliCommand($wp_cli_command);
        } catch (FailedToCallWpCliCommand|InvalidArgumentException $exception) {
            throw new FailedToRunWpCliCommand($exception);
        }
    }

    /**
     * @param string $wp_cli_command
     * @return Response
     * @throws FailedToRunWpCliCommand
     * @throws WpCliCommandReturnedNonZero
     */
    protected function runWpCliCommandSilently(string $wp_cli_command): Response
    {
        try {
            return $this->resolveCommandModifiers($wp_cli_command)->callWpCliCommandSilently($wp_cli_command);
        } catch (FailedToCallWpCliCommand|InvalidArgumentException $exception) {
            throw new FailedToRunWpCliCommand($exception);
        }
    }

    /**
     * @param string $wp_cli_command
     * @return Response
     * @throws FailedToRunWpCliCommand
     */
    protected function runWpCliCommandSilentlyWithoutInterruption(string $wp_cli_command): Response
    {
        try {
            return $this->resolveCommandModifiers($wp_cli_command)
                ->callWpCliCommandSilentlyWithoutInterruption($wp_cli_command);
        } catch (FailedToCallWpCliCommand|InvalidArgumentException $exception) {
            throw new FailedToRunWpCliCommand($exception);
        }
    }

    /**
     * @param string $wp_cli_command
     * @return Response
     * @throws FailedToRunWpCliCommand
     */
    protected function runWpCliCommandWithoutInterruption(string $wp_cli_command): Response
    {
        try {
            return $this->resolveCommandModifiers($wp_cli_command)
                ->callWpCliCommandWithoutInterruption($wp_cli_command);
        } catch (FailedToCallWpCliCommand|InvalidArgumentException $exception) {
            throw new FailedToRunWpCliCommand($exception);
        }
    }
}
