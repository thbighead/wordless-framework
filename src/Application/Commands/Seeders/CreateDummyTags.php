<?php

namespace Wordless\Application\Commands\Seeders;


use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Infrastructure\Wordpress\Taxonomy\Enums\StandardTaxonomy;

class CreateDummyTags extends CreateDummyTaxonomyTerms
{
    use LoadWpConfig;

    public const COMMAND_NAME = 'generate:tags';

    private const HOW_MANY_TAGS = 5;

    protected function description(): string
    {
        return 'A custom command to create dummy tags.';
    }

    /**
     * @return int
     * @throws Exception
     * @throws ExceptionInterface
     */
    protected function runIt(): int
    {
        $this->callConsoleCommand(
            CreateDummyTaxonomyTerms::COMMAND_NAME,
            [
                CreateDummyTaxonomyTerms::OPTION_TAXONOMY_SHORTCUT => [StandardTaxonomy::tag->name],
                '--total' => $this->getTotalTagsToCreate()
            ],
        );

        return Command::SUCCESS;
    }

    private function getTotalTagsToCreate(): int
    {
        return (int)(($this->input->getOption(self::OPTION_TOTAL) ?: null) ?? self::HOW_MANY_TAGS);
    }
}
