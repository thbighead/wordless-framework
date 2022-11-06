<?php

namespace Wordless\Tests\Contracts;

use Wordless\Commands\InitializeTestEnvironment;
use Wordless\Exceptions\CliReturnedNonZero;
use Wordless\Exceptions\FailedToChangeDirectoryTo;
use Wordless\Exceptions\FailedToGetCurrentWorkingDirectory;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\DirectoryFiles;

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

        parent::setUpBeforeClass();

        self::restartTestEnvironment();

        DirectoryFiles::changeWorkingDirectoryDoAndGoBack(
            InitializeTestEnvironment::TARGET_DIRECTORY_NAME,
            function () {
                self::loadWpConfig();
            }
        );
    }

    private static function loadComposerAutoload()
    {
        require_once __DIR__ . '/../vendor/autoload.php';
    }

    private static function loadWpConfig()
    {
        require_once __DIR__ . '/../test-environment/wp/wp-core/wp-config.php';
    }

    /**
     * @return void
     * @throws CliReturnedNonZero
     */
    private static function restartTestEnvironment()
    {
        if (!self::$environment_restarted) {
            passthru($command = 'php console test:environment -f --drop-db', $result_code);

            if ($result_code) {
                throw new CliReturnedNonZero($command, $result_code);
            }

            self::$environment_restarted = true;
        }
    }
}
