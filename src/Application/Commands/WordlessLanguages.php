<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Exceptions\FailedToRunCommand;
use Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory;
use Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory\JsonTranslationFactory;
use Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory\PhpTranslationFactory;
use Wordless\Application\Commands\WordlessLanguages\TranslationsDiscover;
use Wordless\Application\Commands\WordlessLanguages\TranslationsDiscover\Exceptions\DiscoverFailed;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class WordlessLanguages extends ConsoleCommand
{
    final public const COMMAND_NAME = 'wordless:languages';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Replace Wordpress translations.';
    }

    protected function help(): string
    {
        return 'Replace Wordpress and plugins translations from wp-content/languages.';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [];
    }

    /**
     * @return int
     * @throws FailedToRunCommand
     */
    protected function runIt(): int
    {
        try {
            if (empty($languages = Config::wordpress(Config::KEY_LANGUAGES))) {
                $this->writelnDanger(
                    'Missing config key ' . Config::KEY_LANGUAGES . ' in wordpress.php configuration file.'
                );

                return Command::FAILURE;
            }

            foreach ($languages as $language) {
                try {
                    $custom_absolute_directory_path = ProjectPath::root("languages/$language");
                } catch (PathNotFoundException) {
                    $this->writelnInfo("No custom translations found for $language, skipping.");
                    continue;
                }

                foreach (DirectoryFiles::recursiveRead($custom_absolute_directory_path) as $custom_absolute_filepath) {
                    $translationDiscover = new TranslationsDiscover($custom_absolute_filepath, $language);

                    foreach ($translationDiscover->discover() as $wp_absolute_filepath) {
                        $this->wrapScriptWithMessages(
                            "Rewriting $wp_absolute_filepath...",
                            function () use ($wp_absolute_filepath, $custom_absolute_filepath) {
                                $this->buildFactory($wp_absolute_filepath)
                                    ->addCustomTranslations($custom_absolute_filepath)
                                    ->rewrite();
                            }
                        );
                    }
                }
            }
        } catch (InvalidDirectory|PathNotFoundException|DiscoverFailed $exception) {
            throw new FailedToRunCommand(self::COMMAND_NAME, $exception);
        }

        return Command::SUCCESS;
    }

    private function buildFactory(string $wp_absolute_filepath): TranslationFactory
    {
        return $this->isJsonFile($wp_absolute_filepath)
            ? new JsonTranslationFactory($wp_absolute_filepath)
            : new PhpTranslationFactory($wp_absolute_filepath);
    }

    private function isJsonFile(string $filepath): bool
    {
        return Str::endsWith($filepath, '.json');
    }
}

