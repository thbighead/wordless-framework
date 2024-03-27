<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Traits;

use Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Callers;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Resolvers;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Runners;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

trait RunWpCliCommand
{
    use Callers;
    use Resolvers;
    use Runners;

    /**
     * @return OptionDTO[]
     */
    protected function mountRunWpCliOptions():array
    {
        return [
            $this->mountAllowRootModeOption(),
            $this->mountNoTtyOption(),
        ];
    }
}
