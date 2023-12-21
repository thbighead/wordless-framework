<?php

namespace Wordless\Application\Commands\RunTests\Traits;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;

trait FilterOption
{
    private const OPTION_FILTER_NAME = 'filter';
    private const FILTER_SEPARATOR_MARK = '::';

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

            $filters_string = "$filters_string {$this->prepareFilterAsString($filter)}";
        }

        return empty($filters_string) ? null : $filters_string;
    }

    private function mountFilterOption(): OptionDTO
    {
        return OptionDTO::make(
            self::OPTION_FILTER_NAME,
            'Filters what tests should run. You may pass multiple filters to use them together with a logical and. The filter pattern works as follows: test/class/relative/filepath/from/tests/directory.php[::methodNamePattern]',
            mode: OptionMode::array_required_values
        );
    }

    private function prepareFilterAsString(string $filter): string
    {
        if (!Str::contains($filter, self::FILTER_SEPARATOR_MARK)) {
            $filter .= self::FILTER_SEPARATOR_MARK;
        }

        [$test_class_relative_filepath_from_tests, $methods_pattern] = explode(
            self::FILTER_SEPARATOR_MARK,
            $filter
        );

        $test_class_relative_filepath_from_tests = Str::startWith(
            $test_class_relative_filepath_from_tests,
            'tests/'
        );

        return "--filter $methods_pattern $test_class_relative_filepath_from_tests";
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
