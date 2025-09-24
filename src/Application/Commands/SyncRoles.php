<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Wordpress\Models\Role;
use Wordless\Wordpress\Models\Role\Traits\Repository\Traits\FromDatabase\Traits\Sync\Exceptions\SynchroniseFailed;

class SyncRoles extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'roles:sync';
    final public const CONFIG_KEY_PERMISSIONS = 'permissions';

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
     * @throws SynchroniseFailed
     */
    protected function runIt(): int
    {
        $this->wrapScriptWithMessages('Creating or updating roles...', function () {
            Role::sync();
        });

        return Command::SUCCESS;
    }
}
