<?php

namespace Wordless\Application\Commands\RunTests\Traits;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\RunTests\Traits\OutputOption\Enums\TestOutput;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;

trait OutputOption
{
    private const OPTION_OUTPUT_NAME = 'output';

    private TestOutput $test_output_format;

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    private function getTestOutputFormat(): string
    {
        if (isset($this->test_output_format)) {
            return $this->test_output_format->value;
        }

        $test_output_format = TestOutput::tryFrom((string)$this->input->getOption(self::OPTION_OUTPUT_NAME));

        return ($this->test_output_format = $test_output_format ?? TestOutput::testdox)->value;
    }

    private function mountOutputOption(): OptionDTO
    {
        return OptionDTO::make(
            self::OPTION_OUTPUT_NAME,
            'Format test results output. It accepts the following values: testdox (default), teamcity or regular.',
            mode: OptionMode::required_value
        );
    }
}
