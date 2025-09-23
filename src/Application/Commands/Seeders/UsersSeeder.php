<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Helper\ProgressBar;
use Wordless\Application\Commands\Exceptions\FailedToGetCommandOptionValue;
use Wordless\Application\Commands\Seeders\Contracts\SeederCommand;
use Wordless\Application\Commands\Seeders\UsersSeeder\Exceptions\FailedToGenerateUsers;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Exceptions\FailedToRunWpCliCommand;

class UsersSeeder extends SeederCommand
{
    final public const COMMAND_NAME = 'seeder:users';

    protected const DEFAULT_NUMBER_OF_OBJECTS = 20;

    protected function description(): string
    {
        return 'A command to create dummy users.';
    }

    protected function help(): string
    {
        return 'Creates a given number of dummy users. Default is '
            . static::DEFAULT_NUMBER_OF_OBJECTS
            . '.';
    }

    /**
     * @return int
     * @throws FailedToGenerateUsers
     * @throws FailedToGetCommandOptionValue
     */
    protected function runIt(): int
    {
        $progressBar = $this->progressBar($this->getQuantity());
        $progressBar->setMessage('Creating Users...');
        $progressBar->start();

        $this->generateUsers($progressBar);

        $progressBar->setMessage("Done! A total of {$this->getQuantity()} users were generated.");
        $progressBar->finish();

        return Command::SUCCESS;
    }

    /**
     * @param ProgressBar $progressBar
     * @return void
     * @throws FailedToGenerateUsers
     */
    private function generateUsers(ProgressBar $progressBar): void
    {
        try {
            for ($i = 0; $i < $this->getQuantity(); $i++) {
                $user_name = $this->faker->userName();
                $user_email = $this->faker->safeEmail();

                $progressBar->setMessage("Creating user $user_name with e-mail $user_email.");

                $this->runWpCliCommandSilently("user create $user_name $user_email --porcelain --quiet");
            }
        } catch (FailedToGetCommandOptionValue|FailedToRunWpCliCommand|WpCliCommandReturnedNonZero $exception) {
            throw new FailedToGenerateUsers($exception);
        }
    }
}
