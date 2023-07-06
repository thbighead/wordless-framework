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
    private const USER_META_TABLE_META_KEYS_COLUMN_VALUES = ['first_name', 'last_name', 'nickname'];

    private wpdb $databaseConnection;
    private Generator $faker;
    private ?array $configurations;
    protected static $defaultName = 'db:overwrite';
    public ?string $available_environment = Environment::LOCAL;

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
                $this->updateWpUsersMetaTable($user_fake_data);

                if (!$response) {
                    $this->writelnDanger("\nThe process of update User N°$i get an error!");
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
                'user_login' => $user_fake_data['full_dotted_name'],
                'user_pass' => $hashed_password,
                'user_nicename' => $user_fake_data['nickname'],
                'user_email' => $user_fake_data['safe_email'],
                'user_activation_key' => $user_fake_data['has_activation_key']
                    ? $this->generateHashedActivationKey()
                    : '',
                'display_name' => $user_fake_data['first_name'] . ' ' . $user_fake_data['last_name'],
            ],
            ['ID' => $user_fake_data['id']]
        );
    }

    private function generateHashedActivationKey(): string
    {
        return time() . ':' . (new PasswordHash(8, true))->HashPassword(wp_generate_password(20, false));
    }

    private function mountUserFakeData(object $user_data): array
    {
        $fake_first_name = $this->faker->firstName;
        $fake_last_name = $this->faker->lastName;
        $full_dotted_name = "$fake_first_name.$fake_last_name";

        return [
            'id' => $user_data->ID,
            'first_name' => $fake_first_name,
            'last_name' => $fake_last_name,
            'full_dotted_name' => $full_dotted_name,
            'nickname' => "{$fake_first_name}_$fake_last_name",
            'safe_email' => strtolower($full_dotted_name)
                . '@'
                . explode('@', $this->faker->safeEmail)[1],
            'has_activation_key' => $user_data->user_activation_key !== '',
        ];
    }

    private function updateWpUsersMetaTable(array $user_fake_data): void
    {
        foreach (self::USER_META_TABLE_META_KEYS_COLUMN_VALUES as $meta_key_value) {
            $this->databaseConnection->update(
                'wp_usermeta',
                ['meta_value' => $user_fake_data[$meta_key_value]],
                ['user_id' => $user_fake_data['id'], 'meta_key' => $meta_key_value]
            );
        }
    }
}
