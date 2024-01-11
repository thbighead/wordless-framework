<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Migrate\Traits;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Migrate\Exceptions\FailedToFindExecutedMigrationScript;
use Wordless\Application\Commands\Migrate\Exceptions\FailedToFindMigrationScript;
use Wordless\Application\Commands\Traits\ForceMode as BaseForceMode;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

trait ForceMode
{
    use BaseForceMode;

    protected function trashMigrationsOption(): void
    {
        $this->wrapScriptWithMessages(
            'Trashing ' . self::MIGRATIONS_WP_OPTION_NAME . '...',
            function () {
                update_option(
                    self::MIGRATIONS_WP_OPTION_NAME,
                    $this->executed_migrations_list = []
                );
            }
        );
    }

    /**
     * @return $this
     * @throws FailedToFindExecutedMigrationScript
     * @throws FailedToFindMigrationScript
     * @throws InvalidArgumentException
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private function resolveForceMode(): static
    {
        if ($this->isForceMode()) {
            $this->writelnWarning(
                'Running migration into force mode. Rolling back every executed migration.'
            );

            foreach ($this->executedMigrationsOrderedByExecutionDescending() as $executed_migration_filename) {
                $this->executeMigrationScriptFile($executed_migration_filename, false);
            }

            $this->trashMigrationsOption();
        }

        return $this;
    }
}
