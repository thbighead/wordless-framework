<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits;

trait SignalResolver
{
    private bool $has_been_interrupted = false;

    /**
     * @return int[]
     */
    public function getSubscribedSignals(): array
    {
        return [SIGINT, SIGTERM];
    }

    public function handleSignal(int $signal): void
    {
        $this->writelnWarning("\nAborted by user, exiting safely...");
        $this->has_been_interrupted = true;
    }

    protected function hasBeenInterrupted(): bool
    {
        return $this->has_been_interrupted;
    }

    protected function resolveCommandIfInterrupted(): void
    {
        if ($this->has_been_interrupted) {
            $this->writelnComment('Gracefully exiting script.');
            exit;
        }
    }
}
