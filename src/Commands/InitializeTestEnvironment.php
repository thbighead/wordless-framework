<?php

namespace Wordless\Commands;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordless\Adapters\WordlessCommand;
use Wordless\Contracts\Command\AllowRootMode;
use Wordless\Contracts\Command\ForceMode;
use Wordless\Exception\CliReturnedNonZero;
use Wordless\Exception\FailedToChangeDirectoryTo;
use Wordless\Exception\FailedToCopyFile;
use Wordless\Exception\FailedToCreateDirectory;
use Wordless\Exception\FailedToDeletePath;
use Wordless\Exception\FailedToGetDirectoryPermissions;
use Wordless\Exception\FailedToInstallTestEnvironmentThroughComposer;
use Wordless\Exception\PathNotFoundException;
use Wordless\Helpers\Arr;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\ProjectPath;

class InitializeTestEnvironment extends WordlessCommand
{
    use ForceMode, AllowRootMode;

    protected static $defaultName = 'test:environment';

    private const ALLOW_ROOT_MODE = 'allow-root';
    private const FORCE_MODE = 'force';
    private const TARGET_DIRECTORY_NAME = 'test-environment';

    private array $option_inputs;

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Initialize a test environment inside of ' . self::TARGET_DIRECTORY_NAME . ' directory';
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     * @throws FailedToCopyFile
     * @throws FailedToCreateDirectory
     * @throws FailedToDeletePath
     * @throws FailedToGetDirectoryPermissions
     * @throws FailedToInstallTestEnvironmentThroughComposer
     * @throws PathNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        parent::execute($input, $output);

        try {
            $test_environment_directory_path = ProjectPath::root(self::TARGET_DIRECTORY_NAME);

            if ($this->isForceMode()) {
                DirectoryFiles::recursiveDelete($test_environment_directory_path);

                $this->installTestEnvironmentThroughComposer();
            }
        } catch (PathNotFoundException $exception) {
            $test_environment_directory_path = ProjectPath::root() . self::TARGET_DIRECTORY_NAME;

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

        $this->option_inputs = $this->extractOptionsFromOriginalInput();

        $this->option_inputs = Arr::except($this->option_inputs, ['--' . self::FORCE_MODE => true]);
        $this->executeConsoleCommandInsideTestEnvironment('wordless:install', $this->option_inputs);
        $this->executeConsoleCommandInsideTestEnvironment('wordless:deploy', $this->option_inputs);

        return Command::SUCCESS;
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
            )
        ];
    }

    /**
     * @return void
     * @throws CliReturnedNonZero
     * @throws FailedToChangeDirectoryTo
     * @throws PathNotFoundException
     */
    private function executeComposerInstallInsideTestEnvironment()
    {
        if (!chdir($test_environment_path = ProjectPath::root(self::TARGET_DIRECTORY_NAME))) {
            throw new FailedToChangeDirectoryTo($test_environment_path);
        }

        $command = 'composer install';

        if ($command_result = $this->executeCommand($command)) {
            throw new CliReturnedNonZero($command, $command_result);
        }

        if (!chdir($root_path = ProjectPath::root())) {
            throw new FailedToChangeDirectoryTo($root_path);
        }
    }

    /**
     * @param string $command
     * @param array $options
     * @return void
     * @throws CliReturnedNonZero
     * @throws FailedToChangeDirectoryTo
     * @throws PathNotFoundException
     */
    private function executeConsoleCommandInsideTestEnvironment(string $command, array $options)
    {
        if (!chdir($test_environment_path = ProjectPath::root(self::TARGET_DIRECTORY_NAME))) {
            throw new FailedToChangeDirectoryTo($test_environment_path);
        }

        $command = "php console $command";

        foreach ($options as $option_name => $option_value) {
            if ($option_value) {
                $command = "$command $option_name";
            }
        }

        if ($command_result = $this->executeCommand($command)) {
            throw new CliReturnedNonZero($command, $command_result);
        }

        if (!chdir($root_path = ProjectPath::root())) {
            throw new FailedToChangeDirectoryTo($root_path);
        }
    }

    private function extractOptionsFromOriginalInput(): array
    {
        $extracted_options = [];

        foreach ($this->input->getOptions() as $option_name => $is_active) {
            $extracted_options["--$option_name"] = $is_active;
        }

        return $extracted_options;
    }

    /**
     * @return void
     * @throws FailedToDeletePath
     * @throws FailedToInstallTestEnvironmentThroughComposer
     * @throws PathNotFoundException
     */
    private function installTestEnvironmentThroughComposer()
    {
        $command = 'composer create-project thbighead/wordless '
            . self::TARGET_DIRECTORY_NAME
            . ' --no-install --no-scripts --quiet';
        $this->output->writeln("Executing '$command'");
        if ($this->executeCommand($command) !== Command::SUCCESS) {
            throw new FailedToInstallTestEnvironmentThroughComposer($command);
        }

        DirectoryFiles::delete(ProjectPath::root(self::TARGET_DIRECTORY_NAME . '/composer.lock'));
    }
}