<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Abstractions\StubMounters\WpLoadMuPluginsStubMounter;
use Wordless\Adapters\WordlessCommand;
use Wordless\Exceptions\FailedToCopyStub;
use Wordless\Exceptions\InternalCacheNotLoaded;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Arr;
use Wordless\Helpers\Config;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class GenerateMustUsePluginsLoader extends WordlessCommand
{
    public const COMMAND_NAME = 'mup:loader';
    private const SLASH = '/';
    private const WP_LOAD_MU_PLUGINS_FILENAME = 'wp-load-mu-plugins.php';
    protected static $defaultName = self::COMMAND_NAME;

    private string $mu_plugins_directory_path;
    private array $mu_plugins_extra_rules;

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
    )
    {
        $include_files_script .= empty($include_files_script) ?
            $include_once_file_partial_script :
            PHP_EOL . $include_once_file_partial_script;
    }

    private function extractRelativeFilePath(string $filepath)
    {
        return str_replace('\\', self::SLASH, Str::startWith(
            Str::after($filepath, $this->mu_plugins_directory_path),
            DIRECTORY_SEPARATOR
        ));
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
    private function mountIncludeFilesScriptByMuPluginsConfigExtraRules(string &$include_files_script)
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
                    } catch (PathNotFoundException $exception) {
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
    private function mountIncludeFilesScriptByReadingMuPluginsDirectory(string &$include_files_script)
    {
        foreach (DirectoryFiles::recursiveRead($this->mu_plugins_directory_path) as $filepath) {
            if (!Str::endsWith($filepath, '.php')) {
                continue;
            }

            try {
                ProjectPath::wpMustUsePlugins(basename($filepath));
                continue;
            } catch (PathNotFoundException $exception) {
                $relative_file_path = $this->extractRelativeFilePath($filepath);

                if (!($include_once_file_partial_script = $this->mountAutomaticPartialScript($relative_file_path))) {
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
