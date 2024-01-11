<?php

namespace Wordless\Application\Commands\Seeder;


use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Infrastructure\Wordpress\Taxonomy\Enums\StandardTaxonomy;

class CreateDummyCategories extends Seeder
{
    use LoadWpConfig;

    public const COMMAND_NAME = 'generate:categories';
    private const HOW_MANY_CATEGORIES = 5;

    protected function description(): string
    {
        return 'A custom command to create dummy categories.';
    }

    /**
     * @return int
     * @throws Exception
     * @throws ExceptionInterface
     */
    protected function runIt(): int
    {
        $this->callConsoleCommand(
            CreateDummyTaxonomyTerm::COMMAND_NAME,
            [
                CreateDummyTaxonomyTerm::OPTION_TAXONOMY_SHORTCUT => [StandardTaxonomy::category->name],
                '--total' => $this->getTotalCategoriesToCreate()
            ],
        );

        return Command::SUCCESS;
    }

    private function getTotalCategoriesToCreate(): int
    {
        return (int)(($this->input->getOption(self::OPTION_TOTAL) ?: null) ?? self::HOW_MANY_CATEGORIES);
    }
}
