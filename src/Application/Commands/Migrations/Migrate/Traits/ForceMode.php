<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Migrations\Migrate\Traits;

use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\Migrations\Migrate\FlushMigrations;
use Wordless\Application\Commands\Traits\ForceMode as BaseForceMode;

trait ForceMode
{
    use BaseForceMode;

    /**
     * @return $this
     * @throws CliReturnedNonZero
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     */
    private function resolveForceMode(): static
    {
        if ($this->isForceMode()) {
            $this->writelnWarning('Running migration into force mode. Rolling back every executed migration.');

            $this->callConsoleCommand(FlushMigrations::COMMAND_NAME);
        }

        return $this;
    }
}
