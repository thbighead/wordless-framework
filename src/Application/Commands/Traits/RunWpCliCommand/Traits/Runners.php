<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Traits\RunWpCliCommand\Traits;

use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Response;

trait Runners
{
    /**
     * @param string $wp_cli_command
     * @return Response
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    private function runWpCliCommand(string $wp_cli_command): Response
    {
        return $this->resolveCommandModifiers($wp_cli_command)
            ->callWpCliCommand($wp_cli_command);
    }

    /**
     * @param string $wp_cli_command
     * @return Response
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    private function runWpCliCommandSilently(string $wp_cli_command): Response
    {
        return $this->resolveCommandModifiers($wp_cli_command)
            ->callWpCliCommandSilently($wp_cli_command);
    }

    /**
     * @param string $wp_cli_command
     * @return Response
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     */
    private function runWpCliCommandSilentlyWithoutInterruption(string $wp_cli_command): Response
    {
        return $this->resolveCommandModifiers($wp_cli_command)
            ->callWpCliCommandSilentlyWithoutInterruption($wp_cli_command);
    }

    /**
     * @param string $wp_cli_command
     * @return Response
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     */
    private function runWpCliCommandWithoutInterruption(string $wp_cli_command): Response
    {
        return $this->resolveCommandModifiers($wp_cli_command)
            ->callWpCliCommandWithoutInterruption($wp_cli_command);
    }
}
