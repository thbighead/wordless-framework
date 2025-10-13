<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\Database;
use Wordless\Application\Helpers\Database\Exceptions\QueryError;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Application\Helpers\Expect;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;

class BreakPasswords extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'break:passwords';
    private const ARGUMENT_EASY_COMMON_PASSWORD = 'common_password';

    /**
     * @return bool
     * @throws CannotResolveEnvironmentGet
     */
    public function canRun(): bool
    {
        return Environment::isLocal();
    }

    /**
     * @inheritDoc
     */
    protected function arguments(): array
    {
        return [
            ArgumentDTO::make(
                self::ARGUMENT_EASY_COMMON_PASSWORD,
                'The new password of all users.',
                ArgumentMode::required
            ),
        ];
    }

    protected function description(): string
    {
        return 'Changes all users passwords to a given one.';
    }

    protected function help(): string
    {
        return 'Changes all users passwords to a given one. This method is useful for developers to have access of any user in admin.';
    }

    /**
     * @inheritDoc
     */
    protected function options(): array
    {
        return [];
    }

    /**
     * @return int
     * @throws InvalidArgumentException
     * @throws QueryError
     */
    protected function runIt(): int
    {
        $sql_query = 'UPDATE '
            . Database::wpdb()->prefix
            . 'users SET user_hash = "'
            . wp_hash_password(Expect::string(
                $this->input->getArgument(self::ARGUMENT_EASY_COMMON_PASSWORD),
                'password'
            ))
            . '"';

        $this->wrapScriptWithMessages("Running query: $sql_query", function () use ($sql_query) {
            Database::query($sql_query);
        });

        return static::SUCCESS;
    }
}
