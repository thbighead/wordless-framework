<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand;

use Symfony\Component\Console\Command\Command;

final readonly class Response
{
    public function __construct(public int $result_code, public ?string $output = null)
    {
    }

    public function failed(): bool
    {
        return !$this->succeeded();
    }

    public function printedOutput(): bool
    {
        return $this->output === null;
    }

    public function succeeded(): bool
    {
        return $this->result_code === Command::SUCCESS;
    }
}
