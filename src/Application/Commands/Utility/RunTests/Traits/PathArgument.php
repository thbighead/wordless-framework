<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\RunTests\Traits;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;

trait PathArgument
{
    private const ARGUMENT_NAME = 'path';

    private function mountPathArgument(): ArgumentDTO
    {
        return ArgumentDTO::make(
            self::ARGUMENT_NAME,
            'Filters what paths inside tests directory should run.',
            ArgumentMode::optional
        );
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    private function getPathArgument(): string
    {
        $tests_path_option = $this->input->getArgument(self::ARGUMENT_NAME);

        return $tests_path_option === null ? '' : Str::startWith(
            $tests_path_option,
            'tests/'
        );
    }
}
