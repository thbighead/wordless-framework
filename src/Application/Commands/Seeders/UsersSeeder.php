<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders;

use OverflowException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Wordless\Application\Commands\Exceptions\FailedToGetCommandOptionValue;
use Wordless\Application\Commands\Seeders\Contracts\SeederCommand;
use Wordless\Application\Commands\Seeders\UsersSeeder\Exceptions\FailedToGenerateUsers;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Exceptions\FailedToRunWpCliCommand;
use Wordless\Application\Helpers\Timezone;
use Wordless\Exceptions\FailedToRetrieveConfigFromWordpressConfigFile;
use Wordless\Wordpress\Models\Role;

class UsersSeeder extends SeederCommand
{
    final public const COMMAND_NAME = 'seeder:users';
    protected const DEFAULT_NUMBER_OF_OBJECTS = 50;

    /** @var Role[] $roles */
    private array $roles;

    protected function description(): string
    {
        return 'A command to create dummy users.';
    }

    protected function help(): string
    {
        return 'Creates a given number of dummy users for each existing role. Default is '
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
        $total = $this->getQuantity() * count($this->roles());
        $progressBar = $this->progressBar($total);
        $progressBar->setMessage('Creating Users...');
        $progressBar->start();

        $this->generateUsers($progressBar);

        $progressBar->setMessage("Done! A total of $total users were generated.");
        $progressBar->finish();
        $this->writeln('');

        return Command::SUCCESS;
    }

    /**
     * @param ProgressBar $progressBar
     * @return void
     * @throws FailedToGenerateUsers
     */
    private function generateUsers(ProgressBar $progressBar): void
    {
        foreach ($this->roles() as $role) {
            try {
                for ($i = 0; $i < $this->getQuantity(); $i++) {
                    $user_name = $this->faker->unique()->userName();
                    $user_email = $this->faker->unique()->safeEmail();
                    $user_registration_date = $this->faker->dateTimeInInterval(
                        '-3 year',
                        'now',
                        Timezone::forOptionGmtOffset()
                    )->format('Y-m-d-h-i-s');

                    $progressBar->setMessage("Creating user $user_name with e-mail $user_email.");
                    $progressBar->advance(0);

                    $this->runWpCliCommandSilently(
                        "user create $user_name $user_email --porcelain --quiet --role=$role->name --user_registered=$user_registration_date"
                    );

                    $progressBar->advance();
                }
            } catch (FailedToGetCommandOptionValue
            |FailedToRetrieveConfigFromWordpressConfigFile
            |FailedToRunWpCliCommand
            |OverflowException
            |WpCliCommandReturnedNonZero $exception) {
                throw new FailedToGenerateUsers($exception);
            }
        }
    }

    /**
     * @return Role[]
     */
    private function roles(): array
    {
        return $this->roles ?? $this->roles = Role::all();
    }
}
