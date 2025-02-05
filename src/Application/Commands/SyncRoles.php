<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Dotenv\Exception\PathException;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\Role;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToCreateRole;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToFindRole;

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
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws EmptyQueryBuilderArguments
     * @throws FailedToCreateRole
     * @throws FailedToFindRole
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws InvalidProviderClass
     * @throws PathException
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $this->wrapScriptWithMessages('Creating or updating roles...', function () {
            Role::sync();
        });

        return Command::SUCCESS;
    }
}
