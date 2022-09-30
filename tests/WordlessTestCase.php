<?php

namespace Wordless\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Wordless\Commands\InitializeTestEnvironment;
use Wordless\Exceptions\FailedToChangeDirectoryTo;
use Wordless\Exceptions\FailedToGetCurrentWorkingDirectory;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\DirectoryFiles;

abstract class WordlessTestCase extends TestCase
{
    /**
     * @return void
     * @throws ExceptionInterface
     * @throws FailedToChangeDirectoryTo
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws PathNotFoundException
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->restartTestEnvironment();

        DirectoryFiles::changeWorkingDirectoryDoAndGoBack(
            InitializeTestEnvironment::TARGET_DIRECTORY_NAME,
            function () {
                if (!defined('ROOT_PROJECT_PATH')) {
                    define('ROOT_PROJECT_PATH', __DIR__ . '/../test-environment');
                }
            }
        );
    }

    /**
     * @return void
     * @throws ExceptionInterface
     */
    private function restartTestEnvironment()
    {
        $application = new Application;
        $application->find(InitializeTestEnvironment::COMMAND_NAME)
            ->run(new ArrayInput([
                '--' . InitializeTestEnvironment::FORCE_MODE => true,
                '--' . InitializeTestEnvironment::DROP_DB_MODE => true,
            ]), new BufferedOutput);
    }
}
