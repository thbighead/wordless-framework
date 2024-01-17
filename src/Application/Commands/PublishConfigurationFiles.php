<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\PublishConfigurationFiles\Exceptions\FailedToCopyConfig;
use Wordless\Application\Commands\Traits\ForceMode;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCopyFile;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class PublishConfigurationFiles extends ConsoleCommand
{
    use ForceMode;

    final public const COMMAND_NAME = 'publish:config';
    private const CONFIG_FILENAME_ARGUMENT_NAME = 'config_filename';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [
            ArgumentDTO::make(
                self::CONFIG_FILENAME_ARGUMENT_NAME,
                'Configuration filenames to publish. If none passed, all files shall be published.',
                ArgumentMode::array_optional,
                []
            ),
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

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [
            $this->mountForceModeOption('Forces configuration file publishing.'),
        ];
    }

    /**
     * @return int
     * @throws FailedToCopyConfig
     * @throws InvalidArgumentException
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $config_filenames = $this->input->getArgument(self::CONFIG_FILENAME_ARGUMENT_NAME);

        !empty($config_filenames) ?
            $this->publishConfigFilesFromCommandArgument($config_filenames) :
            $this->publishConfigFilesFromVendorPackage();

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
     * @return void
     * @throws FailedToCopyConfig
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     */
    private function publishConfigFilesFromCommandArgument(array $config_filenames): void
    {
        foreach ($config_filenames as $config_filename) {
            $config_filename_with_extension = Str::finishWith($config_filename, '.php');
            $config_relative_filepath = "config/$config_filename_with_extension";
            $config_filepath_from = ProjectPath::assets($config_relative_filepath);

            $this->skipOrCopyConfigFile($config_filepath_from);
        }
    }

    /**
     * @return void
     * @throws FailedToCopyConfig
     * @throws InvalidArgumentException
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private function publishConfigFilesFromVendorPackage(): void
    {
        foreach (DirectoryFiles::recursiveRead(ProjectPath::assets('config')) as $config_filepath_from) {
            $this->skipOrCopyConfigFile($config_filepath_from);
        }
    }

    /**
     * @param string $config_filepath_from
     * @return void
     * @throws FailedToCopyConfig
     * @throws InvalidArgumentException
     */
    private function skipOrCopyConfigFile(string $config_filepath_from): void
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
            $config_filepath_to = $exception->path;

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
