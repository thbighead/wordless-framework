<?php

namespace Wordless\Tests\Contracts;

use Wordless\Application\Commands\Exceptions\CliReturnedNonZero;
use Wordless\Application\Commands\InitializeTestEnvironment;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToChangeDirectoryTo;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToGetCurrentWorkingDirectory;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Exceptions\PathNotFoundException;

trait NeedsTestEnvironment
{
    private static bool $environment_restarted = false;

    /**
     * @return void
     * @throws CliReturnedNonZero
     * @throws FailedToChangeDirectoryTo
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws PathNotFoundException
     */
    public static function setUpBeforeClass(): void
    {
        self::loadComposerAutoload();

        /** @noinspection PhpMultipleClassDeclarationsInspection */
        parent::setUpBeforeClass();

        self::restartTestEnvironment();

        DirectoryFiles::changeWorkingDirectoryDoAndGoBack(
            InitializeTestEnvironment::TARGET_DIRECTORY_NAME,
            function () {
                self::loadWpConfig();
            }
        );
    }

    private static function checkForFlagFile(): bool
    {
        try {
            ProjectPath::realpath(
                __DIR__
                . '/../../'
                . InitializeTestEnvironment::TARGET_DIRECTORY_NAME
                . '/'
                . self::erasableFlagFilename()
            );

            return true;
        } catch (PathNotFoundException $exception) {
            return false;
        }
    }

    private static function erasableFlagFilename(): string
    {
        return 'erase_me_to_force_phpunit_to_restart_test_environment';
    }

    private static function loadComposerAutoload()
    {
        require_once __DIR__ . '/../../vendor/autoload.php';
    }

    private static function loadWpConfig()
    {
        require_once __DIR__ . '/../../test-environment/wp/wp-core/wp-config.php';
    }

    /**
     * @return void
     * @throws CliReturnedNonZero
     * @throws FailedToChangeDirectoryTo
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws PathNotFoundException
     */
    private static function restartTestEnvironment()
    {
        if (!self::checkForFlagFile() && !self::$environment_restarted) {
            passthru($command = 'php console test:environment -f --drop-db', $result_code);

            if ($result_code) {
                throw new CliReturnedNonZero($command, $result_code);
            }

            DirectoryFiles::changeWorkingDirectoryDoAndGoBack(
                InitializeTestEnvironment::TARGET_DIRECTORY_NAME,
                function () {
                    touch(self::erasableFlagFilename());
                }
            );

            self::$environment_restarted = true;
        }
    }
}
