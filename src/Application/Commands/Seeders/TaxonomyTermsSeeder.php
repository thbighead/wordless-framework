<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Helper\ProgressBar;
use Wordless\Application\Commands\Seeders\Contracts\SeederCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder\Enums\ResultFormat;

class TaxonomyTermsSeeder extends SeederCommand
{
    final public const COMMAND_NAME = 'seeder:taxonomy_terms';
    protected const DEFAULT_NUMBER_OF_OBJECTS = 5;

    protected function description(): string
    {
        return 'Creates dummy terms to all registered taxonomies.';
    }

    protected function help(): string
    {
        return 'Creates a given number of dummy terms to each registered taxonomy. Default is '
            . static::DEFAULT_NUMBER_OF_OBJECTS
            . '.';
    }

    /**
     * @return int
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     * @throws EmptyQueryBuilderArguments
     */
    protected function runIt(): int
    {
        /** @var string[] $taxonomies */
        $taxonomies = TaxonomyQueryBuilder::make(ResultFormat::names)->get();

        $progressBar = $this->progressBar($taxinomy_terms_total = count($taxonomies) * $this->getQuantity());
        $progressBar->setMessage('Creating Taxonomy Terms...');
        $progressBar->start();

        foreach ($taxonomies as $taxonomy) {
            $this->generateTaxonomyTerms($taxonomy, $progressBar);
        }

        $progressBar->setMessage("Done! A total of $taxinomy_terms_total taxonomy terms were generated.");
        $progressBar->finish();

        return Command::SUCCESS;
    }

    /**
     * @param string $taxonomy
     * @param ProgressBar $progressBar
     * @return void
     * @throws ExceptionInterface
     * @throws CommandNotFoundException
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    private function generateTaxonomyTerm(string $taxonomy, ProgressBar $progressBar): void
    {
        $new_term = $this->faker->word();

        $progressBar->setMessage("Creating $taxonomy '$new_term'...");

        $this->runWpCliCommandSilently(
            "term create $taxonomy $new_term --description='{$this->faker->paragraph()}' --quiet"
        );

        $progressBar->advance();
    }

    /**
     * @param string $taxonomy
     * @param ProgressBar $progressBar
     * @return void
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws WpCliCommandReturnedNonZero
     */
    private function generateTaxonomyTerms(string $taxonomy, ProgressBar $progressBar): void
    {
        for ($i = 0; $i < $this->getQuantity(); $i++) {
            $this->generateTaxonomyTerm($taxonomy, $progressBar);
        }
    }
}
