<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Commands\Utility\DatabaseOverwrite\DTO\UserDTO;
use Wordless\Application\Commands\Utility\DatabaseOverwrite\DTO\UserDTO\Exceptions\InvalidRawUserData;
use Wordless\Application\Commands\Utility\DatabaseOverwrite\Exceptions\FailedToOverwriteUser;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\ConsoleCommand;
use wpdb;

class DatabaseOverwrite extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'db:overwrite';

    private wpdb $databaseConnection;
    private string $users_table;
    private ?array $configurations;
    private ConfirmationQuestion $tryAgainQuestion;

    public function canRun(): bool
    {
        return Environment::isLocal();
    }

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Overwrites production database dump to remove sensitive personal data complying to LGPD.';
    }

    protected function help(): string
    {
        return 'This command should be used to adapt database for a development environment.';
    }

    protected function options(): array
    {
        return [];
    }

    /**
     * @return int
     * @throws InvalidArgumentException
     * @throws InvalidConfigKey
     * @throws InvalidRawUserData
     * @throws LogicException
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $this->initializeConfigurations()
            ->overwriteAllUsers();

        return Command::SUCCESS;
    }

    private function getTryAgainQuestion(): ConfirmationQuestion
    {
        return $this->tryAgainQuestion ??
            $this->tryAgainQuestion = new ConfirmationQuestion('Try again? (Y/N)');
    }

    /**
     * @return UserDTO[]
     * @throws InvalidRawUserData
     */
    private function getUsers(): array
    {
        $users = [];

        foreach ($this->retrieveRawUsersFromDatabase() as $rawUser) {
            $users[] = new UserDTO($rawUser);
        }

        return $users;
    }

    /**
     * @return $this
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    private function initializeConfigurations(): static
    {
        $this->configurations = Config::wordlessDatabase()->get();
        $this->databaseConnection = new wpdb(
            Environment::get('DB_USER'),
            Environment::get('DB_PASSWORD'),
            Environment::get('DB_NAME'),
            Environment::get('DB_HOST'),
        );
        $this->users_table = Environment::get('DB_TABLE_PREFIX', '') . 'users';

        return $this;
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     * @throws InvalidRawUserData
     * @throws LogicException
     */
    private function overwriteAllUsers(): void
    {
        $users = $this->getUsers();
        $progressBar = new ProgressBar($this->output, count($users));

        $this->writelnInfo('Start overwrite Users...');
        $progressBar->start();

        foreach ($users as $user) {
            $this->tryUntilForfeit($user);

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->writelnSuccess('Finished!');
    }

    /**
     * @param UserDTO $user
     * @return void
     * @throws FailedToOverwriteUser
     */
    private function overwriteUser(UserDTO $user): void
    {
        $result = $this->databaseConnection->update(
                $this->users_table,
                [
                    'user_pass' => $user->password,
                    'user_email' => $user->email,
                    'user_activation_key' => $user->activation_key,
                ],
                ['ID' => $user->id]
            ) !== false;

        if (!$result) {
            throw new FailedToOverwriteUser($user);
        }
    }

    /**
     * @return object[]
     */
    private function retrieveRawUsersFromDatabase(): array
    {
        return $this->databaseConnection->get_results(
            'SELECT ' . implode(',', UserDTO::RAW_ATTRIBUTES) . " FROM $this->users_table"
        );
    }

    /**
     * @param $user
     * @return void
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    private function tryUntilForfeit($user): void
    {
        while (true) {
            try {
                $this->overwriteUser($user);
            } catch (FailedToOverwriteUser $exception) {
                $this->writelnDanger("\n{$exception->getMessage()}");

                if (!$this->ask($this->getTryAgainQuestion())) {
                    break;
                }
            }
        }
    }
}
