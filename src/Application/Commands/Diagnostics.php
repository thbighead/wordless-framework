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
use Wordless\Application\Commands\Traits\NoTtyMode\DTO\NoTtyModeOptionDTO;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Link;
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

