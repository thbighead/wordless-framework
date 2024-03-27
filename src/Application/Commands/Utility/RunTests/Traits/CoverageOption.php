<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\RunTests\Traits;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Utility\RunTests\Traits\CoverageOption\Enums\CoverageFormat;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;

trait CoverageOption
{
    private const OPTION_COVERAGE_NAME = 'cover';

    private function defaultCoverage(): CoverageFormat
    {
        return CoverageFormat::text;
    }

    /**
     * @return CoverageFormat|null
     * @throws InvalidArgumentException
     */
    private function getCoverageOption(): ?CoverageFormat
    {
        $coverage_format = $this->input->getOption(self::OPTION_COVERAGE_NAME);

        if ($coverage_format === false) {
            return null;
        }

        if (empty($coverage_format)) {
            return $this->defaultCoverage();
        }

        return CoverageFormat::from($coverage_format);
    }

    private function mountCoverageOption(): OptionDTO
    {
        return OptionDTO::make(
            self::OPTION_COVERAGE_NAME,
            'Outputs the project test coverage in a given format. The possible formats are '
            . CoverageFormat::stringList()
            . "; default is {$this->defaultCoverage()->value}",
            mode: OptionMode::optional_value
        );
    }

    /**
     * @param string $phpunit_command
     * @return $this
     * @throws InvalidArgumentException
     */
    private function resolveCoverageOptions(string &$phpunit_command): static
    {
        $coverage = $this->getCoverageOption();

        if (!empty($coverage)) {
            $phpunit_command = "$phpunit_command {$coverage->mountForCommand()}";
        }

        return $this;
    }
}
