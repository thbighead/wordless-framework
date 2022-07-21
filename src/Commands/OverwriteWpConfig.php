<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Adapters\WordlessCommand;
use Wordless\Exceptions\FailedToCopyStub;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

class OverwriteWpConfig extends WordlessCommand
{
    protected static $defaultName = 'wp-config:overwrite';
    private const WP_CONFIG_FILENAME = 'wp-config.php';

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Overwrites your wp-config.php by the one defined by your stubs.';
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

        if (!copy($stub_wp_config, $core_wp_config)) {
            throw new FailedToCopyStub($stub_wp_config, $core_wp_config);
        }

        chmod($core_wp_config, $original_core_wp_config_permissions);

        return Command::SUCCESS;
    }

    protected function help(): string
    {
        return 'The file at wp/wp-core/wp-config.php will be overwritten by the one from yous stubs, which maybe defined at this project root at stubs/wp-config.php or use the default one from Wordless from vendor directory. The files permissions are maintained.';
    }

    protected function options(): array
    {
        return [];
    }
}