<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage;

use Symfony\Component\Console\Helper\Table;

trait TabledMessage
{
    /**
     * https://symfony.com/doc/current/components/console/helpers/table.html
     *
     * @return Table
     */
    protected function mountTable(): Table
    {
        return new Table($this->output);
    }
}
