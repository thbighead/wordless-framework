<?php

namespace Wordless\Application\Commands\Seeder;


use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Commands\WpCliCaller;
use Wordless\Application\Helpers\Str;

class CreateDummyCategories extends Seeder
{
    use LoadWpConfig;

    public const COMMAND_NAME = 'generate:categories';

    private const HOW_MANY_CATEGORIES = 5;

    protected function description(): string
    {
        return 'A custom command to create dummy categories';
    }

    /**
     * @return int
     * @throws Exception
     * @throws ExceptionInterface
     */
    protected function runIt(): int
    {
        $this->wrapScriptWithMessages('Creating Categories...', function () {
            for ($i = 0; $i < $this->getTotalCategoriesToCreate(); $i++) {
                $category_description = self::$faker->paragraph();
                $category_name = Str::of(self::$faker->word())->titleCase();
                $full_command = "term create category $category_name --description='$category_description' --quiet";

                $this->callConsoleCommand(
                    WpCliCaller::COMMAND_NAME,
                    [WpCliCaller::WP_CLI_FULL_COMMAND_STRING_ARGUMENT_NAME => $full_command],
                );
            }

            $this->output->write(self::PROGRESS_MARK);
        });

        return Command::SUCCESS;
    }

    private function getTotalCategoriesToCreate(): int
    {
        return (int)$this->input->getOption(self::OPTION_TOTAL) ?? self::HOW_MANY_CATEGORIES;
    }
}
