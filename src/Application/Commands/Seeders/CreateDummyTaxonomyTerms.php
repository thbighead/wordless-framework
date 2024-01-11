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

class CreateDummyTaxonomyTerms extends BaseCreateDummyCommand
{
    use LoadWpConfig;

    public const COMMAND_NAME = 'generate:taxonomy_term';

    private const HOW_MANY_TAXONOMIES = 5;
    private const OPTION_TAXONOMY = 'taxonomy';
    public const OPTION_TAXONOMY_SHORTCUT = '-t';

    protected function description(): string
    {
        return 'A custom command to create dummy taxonomy and terms.';
    }

    protected function options(): array
    {
        return [
            new OptionDTO(
                self::OPTION_TOTAL,
                'Specify the quantity of posts to be created.',
                mode: OptionMode::optional_value,
                default: null,
            ),
            new OptionDTO(
                self::OPTION_TAXONOMY,
                'Specify the names of the taxonomies to be created.',
                self::OPTION_TAXONOMY_SHORTCUT,
                OptionMode::array_required_values,
                []
            ),
        ];
    }

    /**
     * @return int
     * @throws Exception
     * @throws ExceptionInterface
     */
    protected function runIt(): int
    {
        $this->wrapScriptWithMessages('Creating Taxonomies and terms...', function () {
            foreach ($this->getCustomTaxonomies() as $taxonomy) {
                $term_name = $this->faker->word();
                $term_description = $this->faker->paragraph();
                $full_command = "term create $taxonomy $term_name --description='$term_description' --quiet";

                $this->callConsoleCommand(
                    WpCliCaller::COMMAND_NAME,
                    [WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => $full_command],
                );
            }

            $this->output->write(self::PROGRESS_MARK);
        });

        return Command::SUCCESS;
    }

    private function getCustomTaxonomies(): array
    {
        if (!empty($custom_taxonomies = $this->input->getOption(self::OPTION_TAXONOMY))) {
            return $custom_taxonomies;
        }

        return $this->faker->words(self::HOW_MANY_TAXONOMIES);
    }
}
