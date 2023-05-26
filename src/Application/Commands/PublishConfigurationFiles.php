<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Wordless\Application\Commands\PublishConfigurationFiles\Exceptions\FailedToCopyConfig;
use Wordless\Application\Commands\Traits\ForceMode;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToCopyFile;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\ConsoleCommand;

class PublishConfigurationFiles extends ConsoleCommand
{
    use ForceMode;

    protected static $defaultName = 'publish:config';

    private const FORCE_MODE = 'force';
    private const CONFIG_FILENAME_ARGUMENT_NAME = 'config_filename';

    protected function arguments(): array
    {
        return [
            [
                self::ARGUMENT_DEFAULT_FIELD => [],
                self::ARGUMENT_DESCRIPTION_FIELD =>
                    'Configuration filenames to publish. If none passed, all files shall be published.',
                self::ARGUMENT_MODE_FIELD => InputArgument::IS_ARRAY,
                self::ARGUMENT_NAME_FIELD => self::CONFIG_FILENAME_ARGUMENT_NAME,
            ],
        ];
    }

    protected function description(): string
    {
        return 'Publishes configuration files from framework to project root.';
    }

    protected function help(): string
    {
        return 'Copies all configuration files from framework to your project path if they already exists. If want to overwrite files in project root use the force mode.';
    }

    protected function options(): array
    {
        return [
            $this->mountForceModeOption('Forces configuration file publishing.'),
        ];
    }

    /**
     * @return int
     * @throws FailedToCopyConfig
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $config_filenames = $this->input->getArgument(self::CONFIG_FILENAME_ARGUMENT_NAME);

        if (!empty($config_filenames)) {
            $this->publishConfigFilesFromCommandArgument($config_filenames);
        } else {
            $this->publishConfigFilesFromSrcDirectory();
        }

        return Command::SUCCESS;
    }

    /**
     * @param string $from
     * @param string $to
     * @throws FailedToCopyConfig
     */
    private function copyConfig(string $from, string $to): void
    {
        try {
            DirectoryFiles::copyFile($from, $to, false);
        } catch (FailedToCopyFile $exception) {
            throw new FailedToCopyConfig($from, $to, $exception->getSecureMode());
        }
    }

    /**
     * @param array $config_filenames
     * @throws FailedToCopyConfig
     * @throws PathNotFoundException
     */
    private function publishConfigFilesFromCommandArgument(array $config_filenames): void
    {
        foreach ($config_filenames as $config_filename) {
            $config_filename_with_extension = Str::finishWith($config_filename, '.php');
            $config_relative_filepath = "config/$config_filename_with_extension";
            $config_filepath_from = ProjectPath::src($config_relative_filepath);

            $this->skipOrCopiedConfigFile($config_filepath_from);
        }
    }

    /**
     * @throws FailedToCopyConfig
     * @throws PathNotFoundException
     */
    private function publishConfigFilesFromSrcDirectory()
    {
        foreach (DirectoryFiles::recursiveRead(ProjectPath::src('config')) as $config_filepath_from) {
            $this->skipOrCopiedConfigFile($config_filepath_from);
        }
    }

    /**
     * @param string $config_filepath_from
     * @throws FailedToCopyConfig
     * @throws PathNotFoundException
     */
    private function skipOrCopiedConfigFile(string $config_filepath_from)
    {
        $config_relative_filepath = 'config/' . basename($config_filepath_from);

        try {
            $config_filepath_to = ProjectPath::root($config_relative_filepath);
            $this->writeWarning("File destination at $config_filepath_to already exists... ");

            if (!$this->isForceMode()) {
                $this->writelnComment('We are not in force mode, so let\'s skip this copy.');

                return;
            }
        } catch (PathNotFoundException $exception) {
            $config_filepath_to = ProjectPath::root() . DIRECTORY_SEPARATOR . $config_relative_filepath;
            $this->writeComment("File destination at $config_filepath_to does not exists... ");
        }

        $this->wrapScriptWithMessages(
            "Let's copy file from $config_filepath_from to $config_filepath_to.",
            function () use ($config_filepath_from, $config_filepath_to) {
                $this->copyConfig($config_filepath_from, $config_filepath_to);
            }
        );
    }
}
