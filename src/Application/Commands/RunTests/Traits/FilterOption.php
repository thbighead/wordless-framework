<?php

namespace Wordless\Application\Commands\RunTests\Traits;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;

trait FilterOption
{
    private const OPTION_FILTER_NAME = 'filter';

    /**
     * @return string|null
     * @throws InvalidArgumentException
     */
    private function getFilterOption(): ?string
    {
        $filters_string = '';

        foreach ($this->retrieveFiltersFromCli() as $filter) {
            if (!is_string($filter)) {
                continue;
            }

            $filters_string = $filters_string === '' ? $filter : "$filters_string $filter";
        }

        return empty($filters_string) ? null : "--filter $filters_string";
    }

    private function mountFilterOption(): OptionDTO
    {
        return OptionDTO::make(
            self::OPTION_FILTER_NAME,
            'Filters what tests should run. You may pass multiple filters to use them together with a logical and. The filter pattern works as follows: test/class/relative/filepath/from/tests/directory.php[::methodNamePattern]',
            mode: OptionMode::array_required_values
        );
    }

    /**
     * @return array
     * @throws InvalidArgumentException
     */
    private function retrieveFiltersFromCli(): array
    {
        $filters = $this->input->getOption(self::OPTION_FILTER_NAME);

        if (!is_array($filters)) {
            return [];
        }

        return $filters;
    }
}
