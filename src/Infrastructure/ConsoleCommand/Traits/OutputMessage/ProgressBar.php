<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage;

use Symfony\Component\Console\Helper\ProgressBar as SymfonyProgressBar;

trait ProgressBar
{
    protected const PROGRESS_BAR_BASE_FORMAT = ' %current%/%max% [%bar%] %percent:3s%%';

    protected function progressBar(int $max_steps = 0, ?string $format = null): SymfonyProgressBar
    {
        $progressBar = $this->setProgressBarFormat(new SymfonyProgressBar($this->output, $max_steps), $format);

        $progressBar->setMessage('');

        return $progressBar;
    }

    private function setProgressBarFormat(SymfonyProgressBar $progressBar, ?string $format = null): SymfonyProgressBar
    {
        if ($format === null) {
            $base_format = static::PROGRESS_BAR_BASE_FORMAT;
            $v_format = "$base_format %elapsed:6s%";
            $vv_format = "$v_format/%estimated:-6s%";
            $vvv_format = "$vv_format %memory:6s%";

            $format = match (true) {
                    $this->isV() => $v_format,
                    $this->isVV() => $vv_format,
                    $this->isVVV() => $vvv_format,
                    default => $base_format,
                } . ' > %message%';
        }

        $progressBar->setFormat($format);

        return $progressBar;
    }
}
