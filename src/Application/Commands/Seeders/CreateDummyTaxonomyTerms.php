<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders;


use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Wordless\Application\Commands\Seeders\Contracts\BaseCreateDummyCommand;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Commands\WpCliCaller;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;
use Wordless\Wordpress\QueryBuilder\TaxonomyQueryBuilder;

class CreateDummyTaxonomyTerms extends BaseCreateDummyCommand
{
    use LoadWpConfig;

    public const COMMAND_NAME = 'generate:taxonomy_term';

    private const HOW_MANY_TAXONOMIES = 5;
    private const OPTION_TAXONOMY = 'taxonomy';

    protected function description(): string
    {
        return 'A custom command to create dummy taxonomy and terms.';
    }

    protected function help(): string
    {
        return 'This command will help create terms for the registered taxonomies.';
    }

    protected function options(): array
    {
        return [];
    }

    /**
     * @return int
     * @throws Exception
     * @throws ExceptionInterface
     */
    protected function runIt(): int
    {
        $this->wrapScriptWithMessages('Creating Taxonomy Terms ...', function () {
            foreach (TaxonomyQueryBuilder::getInstance()->get() as $taxonomy) {
                $term_name = $this->faker->word();
                $term_description = $this->faker->paragraph();
                $taxonomy_name = $taxonomy->name;

                $full_command = "term create $taxonomy_name $term_name --description='$term_description' --quiet";

                $this->callConsoleCommand(
                    WpCliCaller::COMMAND_NAME,
                    [WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => $full_command],
                );
            }

            $this->output->write(self::PROGRESS_MARK);
        });

        return Command::SUCCESS;
    }
}
