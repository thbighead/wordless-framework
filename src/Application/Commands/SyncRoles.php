<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\OptionDTO;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToCreateRole;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToFindRole;
use Wordless\Wordpress\RolesList;

class SyncRoles extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'roles:sync';

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
        return 'Synchronize roles defined in the permissions config file.';
    }

    protected function help(): string
    {
        return 'This command will update default roles or create custom roles and attach permissions to it.';
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
     * @throws FailedToCreateRole
     * @throws PathNotFoundException
     * @throws FailedToFindRole
     */
    protected function runIt(): int
    {
        $this->wrapScriptWithMessages("Creating or updating roles...", function () {
            RolesList::sync();
        });

        return Command::SUCCESS;
    }
}
