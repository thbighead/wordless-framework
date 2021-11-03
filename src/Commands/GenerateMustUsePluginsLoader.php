<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Abstractions\StubMounters\WpLoadMuPluginsStubMounter;
use Wordless\Adapters\WordlessCommand;
use Wordless\Exception\FailedToCopyStub;
use Wordless\Exception\PathNotFoundException;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class GenerateMustUsePluginsLoader extends WordlessCommand
{
    protected static $defaultName = 'mup:loader';
    private const SLASH = '/';
    private const WP_LOAD_MU_PLUGINS_FILENAME = 'wp-load-mu-plugins.php';

    private array $mu_plugins_extra_rules;

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Generate WordPress Must Use Plugins loader from stub.';
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws FailedToCopyStub
     * @throws PathNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write('Generating MU Plugins autoloader...');

        $mu_plugins_directory_path = ProjectPath::wpMustUsePlugins();
        $wp_load_mu_plugins_destiny_path = Str::finishWith($mu_plugins_directory_path, DIRECTORY_SEPARATOR)
            . self::WP_LOAD_MU_PLUGINS_FILENAME;
        $include_files_script = '';
        $this->mu_plugins_extra_rules = $this->readMuPluginsJson();

        $this->mountIncludeFilesScriptByReadingMuPluginsDirectory(
            $include_files_script,
            $mu_plugins_directory_path
        );

        (new WpLoadMuPluginsStubMounter($wp_load_mu_plugins_destiny_path))->setReplaceContentDictionary([
            '// {include plugins script}' => $include_files_script,
        ])->mountNewFile();

        $output->writeln(' Done!');

        return Command::SUCCESS;
    }

    protected function help(): string
    {
        return 'This is better than looping through directories everytime the project runs.';
    }

    protected function options(): array
    {
        return [];
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

    private function extractRelativeFilePath(string $filepath, string $mu_plugins_directory_path)
    {
        return str_replace('\\', self::SLASH, Str::startWith(
            Str::after($filepath, $mu_plugins_directory_path),
            DIRECTORY_SEPARATOR
        ));
    }

    private function mountAutomaticPartialScript(string $relative_file_path): ?string
    {
        $plugin_directory_name = Str::before(
            self::SLASH,
            Str::after(self::SLASH, $relative_file_path)
        );

        if ($this->mu_plugins_extra_rules[$plugin_directory_name] ?? false) {
            return null;
        }

        return $this->mountPartialScript($relative_file_path);
    }

    private function mountIncludeFilesScriptByMuPluginsJsonExtraRules()
    {
        foreach ($this->mu_plugins_extra_rules as $plugin_directory_name => $relative_php_scripts_path_to_load) {
            $this->concatPartialIncludeScript(
                $include_files_script,
                $this->mountPartialScript(
                    "/$plugin_directory_name/$relative_php_scripts_path_to_load"
                )
            );
        }
    }

    /**
     * @param string $include_files_script
     * @param string $mu_plugins_directory_path
     * @throws PathNotFoundException
     */
    private function mountIncludeFilesScriptByReadingMuPluginsDirectory(
        string &$include_files_script,
        string $mu_plugins_directory_path)
    {
        foreach (DirectoryFiles::recursiveRead($mu_plugins_directory_path) as $filepath) {
            if (!Str::endsWith($filepath, '.php')) {
                continue;
            }

            try {
                ProjectPath::wpMustUsePlugins(basename($filepath));
                continue;
            } catch (PathNotFoundException $exception) {
                $relative_file_path = $this->extractRelativeFilePath($filepath, $mu_plugins_directory_path);

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

    /**
     * @return array
     * @throws PathNotFoundException
     */
    private function readMuPluginsJson(): array
    {
        return json_decode(file_get_contents(ProjectPath::root('mu-plugins.json')), true);
    }
}