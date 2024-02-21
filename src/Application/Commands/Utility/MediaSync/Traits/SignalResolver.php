<?php

namespace App\Commands\MediaSync\Traits;

trait SignalResolver
{
    private bool $has_been_interrupted = false;

    public function getSubscribedSignals(): array
    {
        return $this->signals();
    }

    public function handleSignal(int $signal): void
    {
        if (in_array($signal, $this->signals())) {
            $this->writelnWarning("\nAborted by user, exiting safely...");
            $this->has_been_interrupted = true;
        }
    }

    private function resolveInterruption()
    {
        if ($this->has_been_interrupted) {
            $this->writeln('');
            exit;
        }
    }

    private function signals(): array
    {
        return [SIGINT, SIGTERM];
    }
}
