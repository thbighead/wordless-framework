<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Mounters\Stub\WpLoadMuPluginsStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\OptionDTO;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class GenerateMustUsePluginsLoader extends ConsoleCommand
{
    final public const COMMAND_NAME = 'mup:loader';
    private const SLASH = '/';
    private const WP_LOAD_MU_PLUGINS_FILENAME = 'wp-load-mu-plugins.php';

    protected static $defaultName = self::COMMAND_NAME;

    private string $mu_plugins_directory_path;
    private array $mu_plugins_extra_rules;

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Generate WordPress Must Use Plugins loader from stub.';
    }

    protected function help(): string
    {
        return 'This is better than looping through directories everytime the project runs.';
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
     * @throws FailedToCopyStub
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $this->wrapScriptWithMessages('Generating MU Plugins autoloader...', function () {
            $this->mu_plugins_directory_path = ProjectPath::wpMustUsePlugins();
            $wp_load_mu_plugins_destiny_path = Str::finishWith(
                    $this->mu_plugins_directory_path,
                    DIRECTORY_SEPARATOR
                ) . self::WP_LOAD_MU_PLUGINS_FILENAME;
            $include_files_script = '';
            $this->mu_plugins_extra_rules = Config::tryToGetOrDefault('mu-plugins', []);

            $this->mountIncludeFilesScriptByReadingMuPluginsDirectory($include_files_script);
            $this->mountIncludeFilesScriptByMuPluginsConfigExtraRules($include_files_script);

            (new WpLoadMuPluginsStubMounter($wp_load_mu_plugins_destiny_path))->setReplaceContentDictionary([
                '// {include plugins script}' => $include_files_script,
            ])->mountNewFile();
        });

        return Command::SUCCESS;
    }

    private function concatPartialIncludeScript(
        string &$include_files_script,
        string $include_once_file_partial_script
    ): void
    {
        $include_files_script .= empty($include_files_script) ?
            $include_once_file_partial_script :
            PHP_EOL . $include_once_file_partial_script;
    }

    private function extractRelativeFilePath(string $filepath): string
    {
        return Str::replace(Str::startWith(
            Str::after($filepath, $this->mu_plugins_directory_path),
            DIRECTORY_SEPARATOR
        ), '\\', self::SLASH);
    }

    private function mountAutomaticPartialScript(string $relative_file_path): ?string
    {
        $plugin_directory_name = Str::before(
            Str::after($relative_file_path, self::SLASH),
            self::SLASH
        );

        if ($this->mu_plugins_extra_rules[$plugin_directory_name] ?? false) {
            return null;
        }

        return $this->mountPartialScript($relative_file_path);
    }

    /**
     * @param string $include_files_script
     * @throws PathNotFoundException
     */
    private function mountIncludeFilesScriptByMuPluginsConfigExtraRules(string &$include_files_script): void
    {
        foreach ($this->mu_plugins_extra_rules as $plugin_directory_name => $relative_php_scripts_paths_to_load) {
            if ($relative_php_scripts_paths_to_load === '.') {
                foreach (DirectoryFiles::recursiveRead(
                    ProjectPath::wpMustUsePlugins($plugin_directory_name)
                ) as $filepath_to_load) {
                    if (!Str::endsWith($filepath_to_load, '.php')) {
                        continue;
                    }

                    try {
                        ProjectPath::wpMustUsePlugins(basename($filepath_to_load));
                        continue;
                    } catch (PathNotFoundException) {
                        $this->concatPartialIncludeScript(
                            $include_files_script,
                            $this->mountPartialScript($this->extractRelativeFilePath($filepath_to_load))
                        );
                    }
                }
                continue;
            }

            foreach (Arr::wrap($relative_php_scripts_paths_to_load) as $path_to_load) {
                $this->concatPartialIncludeScript(
                    $include_files_script,
                    $this->mountPartialScript(
                        "/$plugin_directory_name/$path_to_load"
                    )
                );
            }
        }
    }

    /**
     * @param string $include_files_script
     * @throws PathNotFoundException
     */
    private function mountIncludeFilesScriptByReadingMuPluginsDirectory(string &$include_files_script): void
    {
        foreach (DirectoryFiles::recursiveRead($this->mu_plugins_directory_path) as $filepath) {
            if (!Str::endsWith($filepath, '.php')) {
                continue;
            }

            try {
                ProjectPath::wpMustUsePlugins(basename($filepath));
                continue;
            } catch (PathNotFoundException) {
                $relative_file_path = $this->extractRelativeFilePath($filepath);
                $include_once_file_partial_script = $this->mountAutomaticPartialScript($relative_file_path);

                if (!$include_once_file_partial_script) {
                    continue;
                }

                $this->concatPartialIncludeScript($include_files_script, $include_once_file_partial_script);
            }
        }
    }

    private function mountPartialScript(string $relative_file_path): ?string
    {
        return "include_once __DIR__ . '$relative_file_path';";
    }
}
