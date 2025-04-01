<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Commands\Traits\ForceMode;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCopyFile;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class PublishConfigurationFiles extends ConsoleCommand
{
    use ForceMode;

    final public const COMMAND_NAME = 'publish:config';
    private const CONFIG_FILENAME_ARGUMENT_NAME = 'config_filename';

    private array $provided_configs;

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
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FailedToCopyFile
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws InvalidDirectory
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $config_filenames = $this->input->getArgument(self::CONFIG_FILENAME_ARGUMENT_NAME);

        $this->provided_configs = Bootstrapper::bootProvidedConfigs();

        !empty($config_filenames) ?
            $this->publishConfigFilesFromCommandArgument($config_filenames) :
            $this->publishConfigFilesFromVendorPackage();

        return Command::SUCCESS;
    }

    /**
     * @param array $config_filenames
     * @return void
     * @throws FailedToCopyFile
     * @throws InvalidArgumentException
     * @throws PathNotFoundException
     */
    private function publishConfigFilesFromCommandArgument(array $config_filenames): void
    {
        foreach ($config_filenames as $config_filename) {
            $config_filename_with_extension = Str::finishWith($config_filename, '.php');

            if ($this->resolveFromPackageProvider($config_filename_with_extension)) {
                continue;
            }

            $config_relative_filepath = "config/$config_filename_with_extension";
            $config_filepath_from = ProjectPath::assets($config_relative_filepath);

            $this->skipOrCopyConfigFile($config_filepath_from);
        }
    }

    /**
     * @param string $config_filename
     * @return bool
     * @throws FailedToCopyFile
     * @throws InvalidArgumentException
     */
    private function resolveFromPackageProvider(string $config_filename): bool
    {
        if (isset($this->provided_configs[$config_filename])) {
            $this->skipOrCopyConfigFile($this->provided_configs[$config_filename]);

            return true;
        }

        return false;
    }

    /**
     * @return void
     * @throws FailedToCopyFile
     * @throws InvalidArgumentException
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private function publishConfigFilesFromVendorPackage(): void
    {
        foreach (DirectoryFiles::recursiveRead(ProjectPath::assets('config')) as $config_filepath_from) {
            $this->skipOrCopyConfigFile($config_filepath_from);
        }

        foreach ($this->provided_configs as $provided_config) {
            $this->skipOrCopyConfigFile($provided_config);
        }
    }

    /**
     * @param string $config_filepath_from
     * @return void
     * @throws FailedToCopyFile
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
                DirectoryFiles::copyFile($config_filepath_from, $config_filepath_to, false);
            }
        );
    }
}
