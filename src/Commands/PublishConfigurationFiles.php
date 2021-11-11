<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Adapters\WordlessCommand;
use Wordless\Exception\FailedToCopyConfig;
use Wordless\Exception\PathNotFoundException;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class PublishConfigurationFiles extends WordlessCommand
{
    protected static $defaultName = 'publish:config';
    private const FORCE_MODE = 'force';
    private const CONFIG_FILENAME_ARGUMENT_NAME = 'config_filename';

    private array $modes;

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

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws FailedToCopyConfig
     * @throws PathNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->setup($input);

        $config_filenames = $input->getArgument(self::CONFIG_FILENAME_ARGUMENT_NAME);

        foreach ($config_filenames as $config_filename) {
            $config_filename_with_extension = Str::finishWith($config_filename, '.php');
            $config_relative_filepath = "config/$config_filename_with_extension";
            $config_filepath_from = ProjectPath::src($config_relative_filepath);

            try {
                $config_filepath_to = ProjectPath::root($config_relative_filepath);
                if (!$this->isForceMode()) {
                    continue;
                }
            } catch (PathNotFoundException $exception) {
                $config_filepath_to = ProjectPath::root() . DIRECTORY_SEPARATOR . $config_relative_filepath;
            }

            $this->copyConfig($config_filepath_from, $config_filepath_to);
        }

        return Command::SUCCESS;
    }

    protected function help(): string
    {
        return 'Copies all configuration files from framework to your project path if they already exists. If want to overwrite files in project root use the force mode.';
    }

    protected function options(): array
    {
        return [
            [
                self::OPTION_NAME_FIELD => self::FORCE_MODE,
                self::OPTION_SHORTCUT_FIELD => 'f',
                self::OPTION_MODE_FIELD => InputOption::VALUE_NONE,
                self::OPTION_DESCRIPTION_FIELD => 'Forces configuration file publishing.',
            ],
        ];
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

    private function isForceMode(): bool
    {
        return $this->modes[self::FORCE_MODE];
    }

    private function setup(InputInterface $input)
    {
        $this->modes = [
            self::FORCE_MODE => $input->getOption(self::FORCE_MODE),
        ];
    }
}