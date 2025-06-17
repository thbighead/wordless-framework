<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Migrations\Migrate\Traits;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\Migrations\Migrate\FlushMigrations;
use Wordless\Application\Commands\Migrations\Migrate\Traits\ForceMode\Exceptions\FailedToResolveForceMode;
use Wordless\Application\Commands\Traits\ForceMode as BaseForceMode;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\Internal\Exceptions\CallInternalCommandException;

trait ForceMode
{
    use BaseForceMode;

    /**
     * @return $this
     * @throws FailedToResolveForceMode
     */
    private function resolveForceMode(): static
    {
        try {
            if ($this->isForceMode()) {
                $this->writelnWarning('Running migration into force mode. Rolling back every executed migration.');

                $this->callConsoleCommand(FlushMigrations::COMMAND_NAME);
            }

            return $this;
        } catch (CliReturnedNonZero|InvalidArgumentException|CallInternalCommandException $exception) {
            throw new FailedToResolveForceMode($exception);
        }
    }
}
