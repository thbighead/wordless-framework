<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException as SymfonyInvalidArgumentException;
use Wordless\Application\Commands\Diagnostics\Exceptions\FailedToConfigAnalysis;
use Wordless\Application\Commands\Diagnostics\Exceptions\FailedToExecuteAnalysis;
use Wordless\Application\Commands\Diagnostics\Exceptions\FailedToGetTestUrls;
use Wordless\Application\Commands\Diagnostics\Exceptions\FailedToMountComposerInfo;
use Wordless\Application\Commands\Exceptions\FailedToRunCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Exceptions\FailedToCallWpCliCommand;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetFileContent;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Application\Helpers\Link;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\Traits\CallCommand\Traits\External\Exceptions\CallExternalCommandException;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\TabledMessage\Exceptions\FailedToMountTableFromCsvException;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\TabledMessage\Exceptions\FailedToMountTableFromJsonException;
use Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage\TabledMessage\Exceptions\FailedToMountTableFromTsvException;

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
     * @throws FailedToRunCommand
     */
    protected function runIt(): int
    {
        try {
            $this->dotEnvInfo()
                ->phpCliInfo()
                ->composerInfo()
                ->wpConfigAnalysis()
                ->wpCliInfo()
                ->wpExecutionAnalysis()
                ->wpCliDoctorAnalysis();

            return Command::SUCCESS;
        } catch (CallExternalCommandException|CannotResolveEnvironmentGet|FailedToCallWpCliCommand|FailedToConfigAnalysis|FailedToExecuteAnalysis|FailedToMountComposerInfo|FailedToMountTableFromCsvException $exception) {
            throw new FailedToRunCommand(static::COMMAND_NAME, $exception);
        }
    }

    private function checkWpConfigFile(): void
    {
        $this->isWpConfigFilePlaced() ?
            $this->writeln(self::WP_CONFIG_FILENAME . ' placed correctly.') :
            $this->writeln(self::WP_CONFIG_FILENAME . ' in wp-core is not the same from stubs directory!!!');
    }

    /**
     * @return $this
     * @throws FailedToMountComposerInfo
     */
    private function composerInfo(): static
    {
        try {
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
        } catch (CallExternalCommandException|FailedToMountTableFromJsonException|FailedToGetFileContent|PathNotFoundException $exception) {
            throw new FailedToMountComposerInfo($exception);
        }
    }

    private function detachTitleOutput(string $title_output): string
    {
        return Str::wrap(" $title_output ", self::OUTPUT_TITLE_DETACHER);
    }

    /**
     * @return $this
     * @throws CannotResolveEnvironmentGet
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
     * @throws FailedToGetTestUrls
     */
    private function getTestUrls(): array
    {
        try {
            return $this->test_urls ?? $this->test_urls = array_map(function (string $test_url): string {
                return (string)Str::of($test_url)->startWith('/')->startWith(Link::raw());
            }, $this->input->getArgument(self::ARGUMENT_NAME_TEST_URLS));
        } catch (CannotResolveEnvironmentGet|SymfonyInvalidArgumentException $exception) {
            throw new FailedToGetTestUrls($exception);
        }
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
     * @throws CallExternalCommandException
     */
    private function phpCliInfo(): static
    {
        $this->wrapInfoBlock('PHP CLI info', function () {
            $this->writeln(preg_replace(
                '/^(\$_SERVER\[[\'"])?(DB_NAME|DB_USER|DB_PASSWORD|DB_HOST)([\'"]])? => .*$/m',
                '',
                $this->callExternalCommandSilentlyWithoutInterruption('php -i')->output
            ));
        })->writeBlocksSeparator();

        return $this;
    }

    /**
     * @return void
     * @throws CannotResolveEnvironmentGet
     * @throws FailedToCallWpCliCommand
     * @throws FailedToMountTableFromCsvException
     */
    private function wpCliDoctorAnalysis(): void
    {
        $this->wrapInfoBlock('WP CLI doctor Analysis', function () {
            $config_flag = '';
            $environment = Environment::get('APP_ENV', 'undefined');

            try {
                $config_filename = "doctor.$environment.yml";
                $config_filepath = ProjectPath::stubs($config_filename);
                $config_flag = " --config=$config_filepath";

                $this->writeln("Using $config_filename doctor configuration for environment $environment.");
            } catch (PathNotFoundException $exception) {
                $this->writeln(
                    "{$exception->getMessage()} Using no doctor configuration for environment $environment."
                );
            }

            $this->writeTableFromCsv(
                $this->callWpCliCommandSilentlyWithoutInterruption(
                    "doctor check --format=csv --all$config_flag"
                )->output,
                'WP-CLI doctor check all',
                true
            );
        });
    }

    /**
     * @return $this
     * @throws FailedToCallWpCliCommand
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
     * @throws FailedToConfigAnalysis
     */
    private function wpConfigAnalysis(): static
    {
        try {
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
        } catch (FailedToCallWpCliCommand|FailedToMountTableFromCsvException $exception) {
            throw new FailedToConfigAnalysis($exception);
        }
    }

    /**
     * @return $this
     * @throws FailedToExecuteAnalysis
     */
    private function wpExecutionAnalysis(): static
    {
        try {
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
        } catch (FailedToMountTableFromTsvException|FailedToCallWpCliCommand|FailedToGetTestUrls|FailedToMountTableFromCsvException $exception) {
            throw new FailedToExecuteAnalysis($exception);
        }
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
