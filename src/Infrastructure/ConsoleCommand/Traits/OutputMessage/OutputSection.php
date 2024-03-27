<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage;

use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\OutputSection\Exceptions\OutputSectionNotFound;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\OutputSection\Exceptions\TryingToUseSectionWithInvalidOutputInstance;

trait OutputSection
{
    private ?int $current_output_section_position = null;
    /** @var ConsoleSectionOutput[] $outputSections */
    private array $outputSections = [];
    private OutputInterface $rootOutput;

    protected function getCurrentOutputSectionPosition(): ?int
    {
        return $this->current_output_section_position;
    }

    /**
     * @param int $position
     * @return ConsoleSectionOutput
     * @throws OutputSectionNotFound
     */
    protected function getOutputSection(int $position): ConsoleSectionOutput
    {
        $supposedOutputSection = $this->outputSections[$position] ?? null;

        if (!($supposedOutputSection instanceof ConsoleSectionOutput)) {
            throw new OutputSectionNotFound($position);
        }

        return $supposedOutputSection;
    }

    protected function isOutputingInASection(): bool
    {
        return $this->getCurrentOutputSectionPosition() !== null;
    }

    /**
     * @param bool $use_it_already
     * @return ConsoleSectionOutput
     * @throws TryingToUseSectionWithInvalidOutputInstance
     */
    protected function newOutputSection(bool $use_it_already = true): ConsoleSectionOutput
    {
        $current_output_section_position = count($this->outputSections);
        $this->outputSections[] = $newOutputSection = $this->validateRootOutputType()->section();

        if ($use_it_already) {
            $this->output = $newOutputSection;
            $this->current_output_section_position = $current_output_section_position;
        }

        return $newOutputSection;
    }

    /**
     * @param int $position
     * @return ConsoleSectionOutput
     * @throws OutputSectionNotFound
     */
    protected function useOutputSection(int $position): ConsoleSectionOutput
    {
        return $this->output = $this->getOutputSection($position);
    }

    protected function useRootOutput(): OutputInterface
    {
        return $this->output = $this->rootOutput;
    }

    /**
     * @return ConsoleOutputInterface
     * @throws TryingToUseSectionWithInvalidOutputInstance
     */
    private function validateRootOutputType(): ConsoleOutputInterface
    {
        if (!($this->rootOutput instanceof ConsoleOutputInterface)) {
            throw new TryingToUseSectionWithInvalidOutputInstance($this->rootOutput::class);
        }

        return $this->rootOutput;
    }
}
