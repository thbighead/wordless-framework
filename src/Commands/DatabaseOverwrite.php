<?php

namespace Wordless\Commands;

use Faker\Factory;
use Faker\Generator;
use PasswordHash;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Wordless\Adapters\ConsoleCommand;
use Wordless\Contracts\Command\LoadWpConfig;
use Wordless\Exceptions\InvalidConfigKey;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;
use Wordless\Helpers\Environment;
use Wordless\Helpers\ProjectPath;
use wpdb;

class DatabaseOverwrite extends ConsoleCommand
{
    use LoadWpConfig;

    public const DATABASE_OVERWRITE_KEY = 'database_overwrite_command_parameters';
    public const USER_DEFAULT_PASSWORD = 'default_password';

    private wpdb $databaseConnection;
    private Generator $faker;
    private ?array $configurations;
    protected static $defaultName = 'db:overwrite';

    /**
     * @throws PathNotFoundException
     * @throws InvalidConfigKey
     */
    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->configurations = Config::get('commands')[self::DATABASE_OVERWRITE_KEY];
        $this->faker = Factory::create();
    }

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
        return 'Command to overwrite the production dump to remove personal sensitive data to comply with the LGPD.';
    }

    protected function help(): string
    {
        return ' This command should be used to adapt the database for the development environment.';
    }

    protected function options(): array
    {
        return [];
    }

    protected function runIt(): int
    {
        try {
            ProjectPath::wpCore('wp-admin');
        } catch (PathNotFoundException $exception) {
            $this->writelnDanger('You need run "php console minerva:install" command before!');
            return Command::FAILURE;
        }

        $this->databaseConnection = new wpdb(
            Environment::get('DB_USER'),
            Environment::get('DB_PASSWORD'),
            Environment::get('DB_NAME'),
            Environment::get('DB_HOST'),
        );

        $this->updateUsers();

        return Command::SUCCESS;
    }

    private function updateUsers()
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Try again? (Y/N)', true);

        $users = $this->databaseConnection->get_results('SELECT ID,user_activation_key FROM wp_users');
        $progressBar = new ProgressBar($this->output, count($users));

        $this->writelnInfo('Start overwrite Users');
        $progressBar->start();

        $hashed_password = wp_hash_password($this->configurations[self::USER_DEFAULT_PASSWORD] ?? 'password');

        for ($i = 0; $i < count($users); $i++) {
            $progressBar->advance();

            do {
                $user_fake_data = $this->mountUserFakeData($users[$i]);
                $response = $this->updateWpUsersTable($user_fake_data, $hashed_password);

                if (!$response) {
                    $this->writelnDanger(
                        "\nThe process of update User with ID = {$user_fake_data['id']} get an error!"
                    );

                    $question_response = $helper->ask($this->input, $this->output, $question);

                    if (!$question_response) {
                        break;
                    }
                }
            } while (!$response);
        }

        $this->writelnInfo("\nFinished!");
        $progressBar->finish();
    }

    private function updateWpUsersTable(array $user_fake_data, string $hashed_password)
    {
        return $this->databaseConnection->update(
            'wp_users',
            [
                'user_pass' => $hashed_password,
                'user_email' => $user_fake_data['safe_email'],
                'user_activation_key' => $user_fake_data['has_activation_key']
                    ? $this->generateHashedActivationKey()
                    : '',
            ],
            ['ID' => $user_fake_data['id']]
        );
    }

    private function generateHashedActivationKey(): string
    {
        $key = wp_generate_password(20, false);
        $wp_base_hash = new PasswordHash(8, true);

        return time() . ':' . $wp_base_hash->HashPassword($key);
    }

    private function mountUserFakeData(object $user_data): array
    {
        return [
            'id' => $user_data->ID,
            'safe_email' => strtolower("{$this->faker->firstName}.{$this->faker->lastName}")
                . '@'
                . explode('@', $this->faker->safeEmail)[1],
            'has_activation_key' => $user_data->user_activation_key !== '',
        ];
    }
}
