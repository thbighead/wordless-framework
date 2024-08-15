<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Link;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Url;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Symfony\Component\Console\Exception\InvalidArgumentException as SymfonyInvalidArgumentException;

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
     * @throws ExceptionInterface
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws SymfonyInvalidArgumentException
     */
    protected function runIt(): int
    {
        $this->dotEnvInfo()
            ->wpExecutionAnalysis()
            ->wpCliInfo()
            ->phpCliInfo();

        return Command::SUCCESS;
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

    /**
     * @return $this
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    private function phpCliInfo(): static
    {
        $this->wrapInfoBlock('PHP CLI info', function () {
            $this->callExternalCommandWithoutInterruption('php -i', false);
        })->writeBlocksSeparator();

        return $this;
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
     * @throws DotEnvNotSetException
     * @throws ExceptionInterface
     * @throws FormatException
     * @throws SymfonyInvalidArgumentException
     */
    private function wpExecutionAnalysis(): static
    {
        $this->wrapInfoBlock('WP execution analysis', function () {
            $i = 0;

            do {
                $test_url_option = empty($this->getTestUrls()) ? '' : " --url={$this->getTestUrls()[$i]}";

                if (!empty($test_url_option)) {
                    $this->writeln(Str::wrap(
                        " Analysing URL: {$this->getTestUrls()[$i]} ",
                        self::OUTPUT_TITLE_DETACHER
                    ));
                }

                $this->writeln('Draft');
                $this->callWpCliCommandWithoutInterruption("profile stage$test_url_option");

                $this->writeln('Bootstrap');
                $this->callWpCliCommandWithoutInterruption("profile stage bootstrap$test_url_option");

                $this->writeln('Main Query');
                $this->callWpCliCommandWithoutInterruption("profile stage main_query$test_url_option");

                $this->writeln('Template');
                $this->callWpCliCommandWithoutInterruption("profile stage template$test_url_option");

                $this->writeln('Hooks');
                $this->callWpCliCommandWithoutInterruption("profile hook --all$test_url_option");

                $i++;
            } while (isset($this->getTestUrls()[$i]));
        })->writeBlocksSeparator();

        return $this;
    }

    private function wrapInfoBlock(string $title, callable $info_block): static
    {
        $this->writeln(Str::wrap(" $title (BEGIN) ", self::OUTPUT_TITLE_DETACHER));

        $info_block();

        $this->writeln(Str::wrap(" $title (END) ", self::OUTPUT_TITLE_DETACHER));

        return $this;
    }

    private function writeBlocksSeparator(): void
    {
        $this->writeln(".\n.\n.");
    }
}

