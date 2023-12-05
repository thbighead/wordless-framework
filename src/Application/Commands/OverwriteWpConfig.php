<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirestoryFiles\Exceptions\FailedToCopyFile;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class OverwriteWpConfig extends ConsoleCommand
{
    final public const COMMAND_NAME = 'wp-config:overwrite';
    private const WP_CONFIG_FILENAME = 'wp-config.php';

    protected static $defaultName = self::COMMAND_NAME;

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Overwrites your wp-config.php by the one defined by your stubs.';
    }

    protected function help(): string
    {
        return 'The file at wp/wp-core/wp-config.php will be overwritten by the one from yous stubs, which maybe defined at this project root at stubs/wp-config.php or use the default one from Wordless from vendor directory. The files permissions are maintained.';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [];
    }

    /**
     * @return int
     * @throws FailedToCopyStub
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $core_wp_config = ProjectPath::wpCore(self::WP_CONFIG_FILENAME);
        $stub_wp_config = ProjectPath::stubs(self::WP_CONFIG_FILENAME);

        $original_core_wp_config_permissions = fileperms($core_wp_config);

        try {
            DirectoryFiles::copyFile($stub_wp_config, $core_wp_config, false);
        } catch (FailedToCopyFile $exception) {
            throw new FailedToCopyStub($stub_wp_config, $core_wp_config, $exception->getSecureMode());
        }

        chmod($core_wp_config, $original_core_wp_config_permissions);

        return Command::SUCCESS;
    }
}
