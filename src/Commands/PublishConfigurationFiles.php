<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Wordless\Adapters\WordlessCommand;
use Wordless\Contracts\Command\ForceMode;
use Wordless\Exceptions\FailedToCopyConfig;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class PublishConfigurationFiles extends WordlessCommand
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
    private function copyConfig(string $from, string $to)
    {
        if (!copy($from, $to)) {
            throw new FailedToCopyConfig($from, $to);
        }
    }

    /**
     * @param array $config_filenames
     * @throws FailedToCopyConfig
     * @throws PathNotFoundException
     */
    private function publishConfigFilesFromCommandArgument(array $config_filenames)
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
            $this->output->write("File destination at $config_filepath_to already exists... ");
            if (!$this->isForceMode()) {
                $this->output->writeln('We are not in force mode, so let\'s skip this copy.');
                return;
            }
        } catch (PathNotFoundException $exception) {
            $config_filepath_to = ProjectPath::root() . DIRECTORY_SEPARATOR . $config_relative_filepath;
            $this->output->write("File destination at $config_filepath_to does not exists... ");
        }

        $this->wrapScriptWithMessages(
            "Let's copy file from $config_filepath_from to $config_filepath_to.",
            function () use ($config_filepath_from, $config_filepath_to) {
                $this->copyConfig($config_filepath_from, $config_filepath_to);
            }
        );
    }
}