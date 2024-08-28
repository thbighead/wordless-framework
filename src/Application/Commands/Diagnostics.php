<?php

namespace Wordless\Application\Commands;

use League\Csv\Exception;
use League\Csv\SyntaxError;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException as SymfonyInvalidArgumentException;
use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetFileContent;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Link;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class Diagnostics extends ConsoleCommand
{
    use RunWpCliCommand;

    final public const COMMAND_NAME = 'diagnostics';
    protected const EXPOSABLE_ENVIRONMENT_VARIABLES = [
        'APP_NAME',
        'APP_ENV',
        'APP_HOST',
        'APP_URL',
        'FRONT_END_URL',
        'DB_CHARSET',
        'DB_COLLATE',
        'DB_TABLE_PREFIX',
        'WORDLESS_CORS',
        'WORDLESS_CSP',
        'WP_DEBUG_DISPLAY',
        'WP_ACCESSIBLE_HOSTS',
    ];
    private const ARGUMENT_NAME_TEST_URLS = 'test_urls';
    private const OUTPUT_TITLE_DETACHER = '==========';
    private const WP_CONFIG_FILENAME = 'wp-config.php';

    /** @var string[] $test_urls */
    private array $test_urls;

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [
            ArgumentDTO::make(
                self::ARGUMENT_NAME_TEST_URLS,
                'The relative URLs to analyse WordPress\' execution (if not defined, defaults to home page).',
                ArgumentMode::array_optional,
                []
            ),
        ];
    }

    protected function description(): string
    {
        return 'A complete overview of your project setup. Useful to check environment options installed.';
    }


    protected function help(): string
    {
        return 'Creates a full report with PHP info (CLI and FPM), development-shareable environment variables and WP CLI profile hook, profile stage, cli info and doctor list commands. It is recommended to output this command to a file suffixing it with \' > wordless-report.txt\'';
    }

    protected function isNoTtyMode(): bool
    {
        return true;
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [
            ...$this->mountRunWpCliOptions(),
        ];
    }

    /**
     * @return int
     * @throws CommandNotFoundException
     * @throws DotEnvNotSetException
     * @throws Exception
     * @throws ExceptionInterface
     * @throws FailedToGetFileContent
     * @throws FailedToParseArrayKey
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws PathNotFoundException
     * @throws SymfonyInvalidArgumentException
     * @throws SyntaxError
     */
    protected function runIt(): int
    {
        $this->dotEnvInfo()
            ->phpCliInfo()
            ->composerInfo()
            ->wpConfigAnalysis()
            ->wpCliInfo()
            ->wpExecutionAnalysis()
            ->wpCliDoctorAnalysis();

        return Command::SUCCESS;
    }

    private function checkWpConfigFile(): void
    {
        $this->isWpConfigFilePlaced() ?
            $this->writeln(self::WP_CONFIG_FILENAME . ' placed correctly.') :
            $this->writeln(self::WP_CONFIG_FILENAME . ' in wp-core is not the same from stubs directory!!!');
    }

    /**
     * @return $this
     * @throws FailedToGetFileContent
     * @throws FailedToParseArrayKey
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws PathNotFoundException
     */
    private function composerInfo(): static
    {
        $this->wrapInfoBlock('Composer INFO', function () {
            $this->writeDetachedTitle('Composer JSON');
            $this->writeln(DirectoryFiles::getFileContent(ProjectPath::root('composer.json')));
            $this->writeTableFromJson(
                $this->callExternalCommandSilentlyWithoutInterruption(
                    'composer show --format=json'
                )->output ?? '',
                'installed',
                'Composer Installed Packages',
                true
            );
        })->writeBlocksSeparator();

        return $this;
    }

    private function detachTitleOutput(string $title_output): string
    {
        return Str::wrap(" $title_output ", self::OUTPUT_TITLE_DETACHER);
    }

    /**
     * @return $this
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
    private function dotEnvInfo(): static
    {
        $this->wrapInfoBlock('.env EXPOSABLE VARIABLES', function () {
            foreach (self::EXPOSABLE_ENVIRONMENT_VARIABLES as $env_variable_name) {
                $this->writeln(
                    "$env_variable_name=\"" . Environment::get($env_variable_name, '') . '"'
                );
            }
        })->writeBlocksSeparator();

        return $this;
    }

    /**
     * @return string[]
     * @throws DotEnvNotSetException
     * @throws FormatException
     * @throws SymfonyInvalidArgumentException
     */
    private function getTestUrls(): array
    {
        return $this->test_urls ?? $this->test_urls = array_map(function (string $test_url): string {
            return (string)Str::of($test_url)->startWith('/')->startWith(Link::raw());
        }, $this->input->getArgument(self::ARGUMENT_NAME_TEST_URLS));
    }

    private function isWpConfigFilePlaced(): bool
    {
        try {
            $wp_config_content = DirectoryFiles::getFileContent(
                ProjectPath::wpCore(self::WP_CONFIG_FILENAME)
            );
            $wp_config_stub_content = DirectoryFiles::getFileContent(
                ProjectPath::stubs(self::WP_CONFIG_FILENAME)
            );

            return $wp_config_content === $wp_config_stub_content;
        } catch (FailedToGetFileContent|PathNotFoundException) {
            return false;
        }
    }

    /**
     * @return $this
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    private function phpCliInfo(): static
    {
        $this->wrapInfoBlock('PHP CLI info', function () {
            $this->writeln(preg_replace(
                '/^(\$_SERVER\[[\'"])?(DB_NAME|DB_USER|DB_PASSWORD|DB_HOST)([\'"]])? => .*$/m',
                '',
                $this->callExternalCommandSilentlyWithoutInterruption('php -i', false)->output
            ));
        })->writeBlocksSeparator();

        return $this;
    }

    private function wpCliDoctorAnalysis(): void
    {
        $this->wrapInfoBlock('WP CLI info', function () {
            $this->callWpCliCommandWithoutInterruption('cli info');
        });
    }

    /**
     * @return $this
     * @throws CommandNotFoundException
     * @throws ExceptionInterface
     */
    private function wpCliInfo(): static
    {
        $this->wrapInfoBlock('WP CLI info', function () {
            $this->callWpCliCommandWithoutInterruption('cli info');
        })->writeBlocksSeparator();

        return $this;
    }

    /**
     * @return $this
     * @throws CommandNotFoundException
     * @throws Exception
     * @throws ExceptionInterface
     * @throws SyntaxError
     */
    private function wpConfigAnalysis(): static
    {
        $this->wrapInfoBlock(self::WP_CONFIG_FILENAME . ' analysis', function () {
            $this->checkWpConfigFile();

            $this->writeTableFromCsv(
                preg_replace(
                    '/^(DB_NAME|DB_USER|DB_PASSWORD|DB_HOST),.*$/m',
                    '',
                    $this->callWpCliCommandSilentlyWithoutInterruption(
                        'config list --format=csv'
                    )->output
                ) ?? '',
                'Config List',
                true
            );
        })->writeBlocksSeparator();

        return $this;
    }

    /**
     * @return $this
     * @throws CommandNotFoundException
     * @throws DotEnvNotSetException
     * @throws ExceptionInterface
     * @throws FormatException
     * @throws SymfonyInvalidArgumentException
     * @throws Exception
     * @throws SyntaxError
     */
    private function wpExecutionAnalysis(): static
    {
        $this->wrapInfoBlock('WP execution analysis', function () {
            $i = 0;

            do {
                $test_url_option = empty($this->getTestUrls()) ? '' : " --url={$this->getTestUrls()[$i]}";

                if (!empty($test_url_option)) {
                    $this->writeDetachedTitle("Analysing URL: {$this->getTestUrls()[$i]}");
                }

                $this->writeTableFromCsv(
                    $this->callWpCliCommandSilentlyWithoutInterruption(
                        "profile stage --format=csv$test_url_option"
                    )->output ?? '',
                    'Draft',
                    true
                );

                $this->writeTableFromTsv(
                    $this->callWpCliCommandSilentlyWithoutInterruption(
                        "profile stage bootstrap $test_url_option"
                    )->output ?? '',
                    'Bootstrap',
                    true
                );

                $this->writeTableFromTsv(
                    $this->callWpCliCommandSilentlyWithoutInterruption(
                        "profile stage main_query $test_url_option"
                    )->output ?? '',
                    'Main Query',
                    true
                );

                $this->writeTableFromTsv(
                    $this->callWpCliCommandSilentlyWithoutInterruption(
                        "profile stage template $test_url_option"
                    )->output ?? '',
                    'Template',
                    true
                );

                $this->writeTableFromCsv(
                    $this->callWpCliCommandSilentlyWithoutInterruption(
                        "profile hook --all --format=csv$test_url_option"
                    )->output ?? '',
                    'Hooks',
                    true
                );

                $i++;
            } while (isset($this->getTestUrls()[$i]));
        })->writeBlocksSeparator();

        return $this;
    }

    private function wrapInfoBlock(string $title, callable $info_block): static
    {
        $this->writeDetachedTitle("$title (BEGIN)");

        $info_block();

        $this->writeDetachedTitle("$title (END)");

        return $this;
    }

    private function writeBlocksSeparator(): void
    {
        $this->writeln(".\n.\n.");
    }

    private function writeDetachedTitle(string $title): void
    {
        $this->writeln($this->detachTitleOutput($title));
    }
}
