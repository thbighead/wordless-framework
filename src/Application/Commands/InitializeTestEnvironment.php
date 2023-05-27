<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\InitializeTestEnvironment\Exceptions\FailedToInstallTestEnvironmentThroughComposer;
use Wordless\Application\Commands\Traits\AllowRootMode;
use Wordless\Application\Commands\Traits\ForceMode;
use Wordless\Application\Commands\Traits\ForceMode\DTO\ForceModeOptionDTO;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToChangeDirectoryTo;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToCopyFile;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToDeletePath;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToGetCurrentWorkingDirectory;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\OptionDTO\Enums\OptionMode;

class InitializeTestEnvironment extends ConsoleCommand
{
    use AllowRootMode, ForceMode, RunWpCliCommand;

    final public const COMMAND_NAME = 'test:environment';

    final public const TARGET_DIRECTORY_NAME = 'test-environment';
    private const DROP_DB_MODE = 'drop-db';

    protected static $defaultName = self::COMMAND_NAME;

    private array $option_inputs;

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Initializes a test environment inside of ' . self::TARGET_DIRECTORY_NAME . ' directory.';
    }

    protected function help(): string
    {
        return 'Installs a Wordless project test environment using a Composer command.';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [
            $this->mountAllowRootModeOption(),
            $this->mountForceModeOption(
                'Deletes everything inside test-environment to install from zero.'
            ),
            new OptionDTO(
                self::DROP_DB_MODE,
                'Also drops test database when in force mode.',
                mode: OptionMode::no_value
            ),
        ];
    }

    /**
     * @return int
     * @throws CliReturnedNonZero
     * @throws FailedToChangeDirectoryTo
     * @throws FailedToCopyFile
     * @throws FailedToCreateDirectory
     * @throws FailedToDeletePath
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws FailedToInstallTestEnvironmentThroughComposer
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        try {
            $test_environment_directory_path = ProjectPath::root(self::TARGET_DIRECTORY_NAME);

            if ($this->isForceMode()) {
                $this->resolveDropTestDatabase();
                $this->wrapScriptWithMessages(
                    "Deleting $test_environment_directory_path...",
                    function () use ($test_environment_directory_path) {
                        DirectoryFiles::recursiveDelete($test_environment_directory_path);
                    }
                );

                $this->installTestEnvironmentThroughComposer();
            }
        } catch (PathNotFoundException) {
            $test_environment_directory_path = ProjectPath::root() . '/' . self::TARGET_DIRECTORY_NAME;

            $this->writelnWhenVerbose("Creating test environment at $test_environment_directory_path.");

            $this->installTestEnvironmentThroughComposer();
        }

        DirectoryFiles::recursiveCopy(
            ProjectPath::root(self::TARGET_DIRECTORY_NAME . '-backup'),
            $test_environment_directory_path,
            [],
            false
        );

        $this->executeComposerInstallInsideTestEnvironment();

        $this->executeConsoleCommandInsideTestEnvironment('wordless:install');
        $this->executeConsoleCommandInsideTestEnvironment('wp:run "post generate"');

        return Command::SUCCESS;
    }

    protected function setup(InputInterface $input, OutputInterface $output): void
    {
        parent::setup($input, $output);

        $this->option_inputs = Arr::except(
            $this->extractOptionsFromOriginalInput(),
            ['--' . ForceModeOptionDTO::FORCE_MODE => true]
        );
    }

    /**
     * @return void
     * @throws CliReturnedNonZero
     * @throws FailedToChangeDirectoryTo
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws PathNotFoundException
     */
    private function resolveDropTestDatabase(): void
    {
        if (!$this->input->getOption(self::DROP_DB_MODE)) {
            return;
        }

        $this->wrapScriptWithMessages('Dropping test database...', function () {
            DirectoryFiles::changeWorkingDirectoryDoAndGoBack(
                ProjectPath::root(self::TARGET_DIRECTORY_NAME),
                function () {
                    $command = 'php console wp:run "db drop --yes --quiet"';

                    if ($command_result = $this->callExternalCommand($command)) {
                        throw new CliReturnedNonZero($command, $command_result);
                    }
                },
                ProjectPath::root()
            );
        });
    }

    /**
     * @return void
     * @throws CliReturnedNonZero
     * @throws FailedToChangeDirectoryTo
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws PathNotFoundException
     */
    private function executeComposerInstallInsideTestEnvironment(): void
    {
        DirectoryFiles::changeWorkingDirectoryDoAndGoBack(
            ProjectPath::root(self::TARGET_DIRECTORY_NAME),
            function () {
                $command = 'composer install';

                if ($command_result = $this->callExternalCommand($command)) {
                    throw new CliReturnedNonZero($command, $command_result);
                }
            },
            ProjectPath::root()
        );
    }

    /**
     * @param string $command
     * @return void
     * @throws CliReturnedNonZero
     * @throws FailedToChangeDirectoryTo
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws PathNotFoundException
     */
    private function executeConsoleCommandInsideTestEnvironment(string $command): void
    {
        DirectoryFiles::changeWorkingDirectoryDoAndGoBack(
            ProjectPath::root(self::TARGET_DIRECTORY_NAME),
            function () use ($command) {
                $command = "php console $command";

                foreach ($this->option_inputs as $option_name => $option_value) {
                    if ($option_value) {
                        $command = "$command $option_name";
                    }
                }

                if ($command_result = $this->callExternalCommand($command)) {
                    throw new CliReturnedNonZero($command, $command_result);
                }
            },
            ProjectPath::root()
        );
    }

    private function extractOptionsFromOriginalInput(): array
    {
        $extracted_options = [];

        foreach ($this->input->getOptions() as $option_name => $is_active) {
            if ($option_name === self::DROP_DB_MODE) {
                continue;
            }

            $extracted_options["--$option_name"] = $is_active;
        }

        return $extracted_options;
    }

    /**
     * @return void
     * @throws FailedToDeletePath
     * @throws FailedToInstallTestEnvironmentThroughComposer
     */
    private function installTestEnvironmentThroughComposer(): void
    {
        $command = 'composer create-project --prefer-dist '
            . ProjectPath::VENDOR_PACKAGE_PROJECT
            . '="@dev" '
            . self::TARGET_DIRECTORY_NAME
            . ' --no-install --no-scripts --quiet --repository="{\"type\":\"path\",\"url\":\"../www\",\"options\":{\"symlink\":false}}"';
        $this->writeln("Executing '$command'");

        if ($this->callExternalCommand($command) !== Command::SUCCESS) {
            throw new FailedToInstallTestEnvironmentThroughComposer($command);
        }

        try {
            DirectoryFiles::delete(ProjectPath::root(self::TARGET_DIRECTORY_NAME . '/composer.lock'));
        } catch (PathNotFoundException $exception) {
            $this->writelnCommentWhenVerbose("{$exception->getMessage()} Skipping deletion.");
        }
    }
}
