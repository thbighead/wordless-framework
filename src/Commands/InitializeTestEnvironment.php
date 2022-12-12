<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Adapters\ConsoleCommand;
use Wordless\Contracts\Command\AllowRootMode;
use Wordless\Contracts\Command\ForceMode;
use Wordless\Contracts\Command\RunWpCliCommand;
use Wordless\Exceptions\CliReturnedNonZero;
use Wordless\Exceptions\FailedToChangeDirectoryTo;
use Wordless\Exceptions\FailedToCopyFile;
use Wordless\Exceptions\FailedToCreateDirectory;
use Wordless\Exceptions\FailedToDeletePath;
use Wordless\Exceptions\FailedToGetCurrentWorkingDirectory;
use Wordless\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Exceptions\FailedToInstallTestEnvironmentThroughComposer;
use Wordless\Exceptions\InvalidDirectory;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Arr;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\ProjectPath;

class InitializeTestEnvironment extends ConsoleCommand
{
    use AllowRootMode, ForceMode, RunWpCliCommand;

    protected static $defaultName = self::COMMAND_NAME;

    public const COMMAND_NAME = 'test:environment';
    public const DROP_DB_MODE = 'drop-db';
    public const FORCE_MODE = 'force';
    public const TARGET_DIRECTORY_NAME = 'test-environment';
    private const ALLOW_ROOT_MODE = 'allow-root';

    private array $option_inputs;

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Initialize a test environment inside of ' . self::TARGET_DIRECTORY_NAME . ' directory';
    }

    protected function help(): string
    {
        return 'This test environment will install a wordless project using Composer command.';
    }

    protected function options(): array
    {
        return [
            $this->mountAllowRootModeOption(),
            $this->mountForceModeOption(
                'Deletes everything inside test-environment to install from zero.'
            ),
            [
                self::OPTION_NAME_FIELD => self::DROP_DB_MODE,
                self::OPTION_MODE_FIELD => InputOption::VALUE_NONE,
                self::OPTION_DESCRIPTION_FIELD => 'Also drops test database when in force mode.',
            ],
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
        } catch (PathNotFoundException $exception) {
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
        $this->executeConsoleCommandInsideTestEnvironment('wordless:deploy');
        $this->executeConsoleCommandInsideTestEnvironment('wp:run "post generate"');

        return Command::SUCCESS;
    }

    protected function setup(InputInterface $input, OutputInterface $output)
    {
        parent::setup($input, $output);

        $this->option_inputs = Arr::except(
            $this->extractOptionsFromOriginalInput(),
            ['--' . self::FORCE_MODE => true]
        );
    }

    /**
     * @return void
     * @throws CliReturnedNonZero
     * @throws FailedToChangeDirectoryTo
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws PathNotFoundException
     */
    private function resolveDropTestDatabase()
    {
        if (!$this->input->getOption(self::DROP_DB_MODE)) {
            return;
        }

        $this->wrapScriptWithMessages('Dropping test database...', function () {
            DirectoryFiles::changeWorkingDirectoryDoAndGoBack(
                ProjectPath::root(self::TARGET_DIRECTORY_NAME),
                function () {
                    $command = 'php console wp:run "db drop --yes --quiet"';

                    if ($command_result = $this->executeCommand($command)) {
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
    private function executeComposerInstallInsideTestEnvironment()
    {
        DirectoryFiles::changeWorkingDirectoryDoAndGoBack(
            ProjectPath::root(self::TARGET_DIRECTORY_NAME),
            function () {
                $command = 'composer install';

                if ($command_result = $this->executeCommand($command)) {
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
    private function executeConsoleCommandInsideTestEnvironment(string $command)
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

                if ($command_result = $this->executeCommand($command)) {
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
    private function installTestEnvironmentThroughComposer()
    {
        $command = 'composer create-project --prefer-dist '
            . ProjectPath::VENDOR_PACKAGE_PROJECT
            . '="@dev" '
            . self::TARGET_DIRECTORY_NAME
            . ' --no-install --no-scripts --quiet --repository="{\"type\":\"path\",\"url\":\"../www\",\"options\":{\"symlink\":false}}"';
        $this->writeln("Executing '$command'");

        if ($this->executeCommand($command) !== Command::SUCCESS) {
            throw new FailedToInstallTestEnvironmentThroughComposer($command);
        }

        try {
            DirectoryFiles::delete(ProjectPath::root(self::TARGET_DIRECTORY_NAME . '/composer.lock'));
        } catch (PathNotFoundException $exception) {
            $this->writelnCommentWhenVerbose("{$exception->getMessage()} Skipping deletion.");
        }
    }
}
