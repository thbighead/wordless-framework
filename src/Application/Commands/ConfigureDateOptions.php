<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\ConfigureDateOptions\Exceptions\FailedToSetTimezone;
use Wordless\Application\Commands\Exceptions\FailedToRunCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Traits\Exceptions\FailedToRunWpCliCommand;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Traits\Internal\Exceptions\FailedToLoadConfigFile;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Application\Helpers\Timezone;
use Wordless\Exceptions\FailedToRetrieveConfigFromWordpressConfigFile;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Wordpress\Enums\StartOfWeek;

class ConfigureDateOptions extends ConsoleCommand
{
    use RunWpCliCommand;

    final public const COMMAND_NAME = 'options:date';
    final public const CONFIG_KEY_ADMIN_DATETIME = 'datetime';
    final public const CONFIG_KEY_ADMIN_DATETIME_DATE_FORMAT = 'date_format';
    final public const CONFIG_KEY_ADMIN_DATETIME_TIME_FORMAT = 'time_format';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Configure application date options.';
    }


    protected function help(): string
    {
        return 'Change Wordpress date options according to config files setup.';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [
            ...$this->mountRunWpCliOptions(),
        ];
    }

    /**
     * @return int
     * @throws FailedToRunCommand
     */
    protected function runIt(): int
    {
        try {
            $dateConfig = Config::wordpressAdmin()->ofKey(self::CONFIG_KEY_ADMIN_DATETIME);

            $this->runWpCliCommand(
                "option update date_format \"{$dateConfig->get(self::CONFIG_KEY_ADMIN_DATETIME_DATE_FORMAT, 'Y-m-d')}\""
            );
            $this->runWpCliCommand(
                "option update time_format \"{$dateConfig->get(self::CONFIG_KEY_ADMIN_DATETIME_TIME_FORMAT, 'H:i')}\""
            );
            $this->runWpCliCommand('option update '
                . StartOfWeek::KEY
                . " {$dateConfig->get(StartOfWeek::KEY, StartOfWeek::sunday->value)}");

            $this->setTimezone();
        } catch (
            EmptyConfigKey|FailedToRetrieveConfigFromWordpressConfigFile|WpCliCommandReturnedNonZero|FailedToRunWpCliCommand|FailedToLoadConfigFile|FailedToSetTimezone $exception
        ) {
            throw new FailedToRunCommand(static::COMMAND_NAME, $exception);
        }

        return Command::SUCCESS;
    }

    /**
     * @return void
     * @throws FailedToSetTimezone
     */
    private function setTimezone(): void
    {
        try {
            $gmt_offset_option_value = Timezone::forOptionGmtOffset();
            $timezone_string_option_value = Timezone::forOptionTimezoneString();
        } catch (FailedToRetrieveConfigFromWordpressConfigFile $exception) {
            throw new FailedToSetTimezone('Failed to retrieve timezone configuration.', $exception);
        }

        try {
            $db_table_prefix = Environment::get('DB_TABLE_PREFIX', 'wp_');
        } catch (CannotResolveEnvironmentGet $exception) {
            throw new FailedToSetTimezone('Failed to retrieve database prefix configuration.', $exception);
        }

        $wp_cli_command = 'db query';
        /** @noinspection SqlNoDataSourceInspection */
        $update_query_prefix = "UPDATE {$db_table_prefix}options SET option_value=";
        $update_option_gmt_offset_query = "$update_query_prefix\"$gmt_offset_option_value\" WHERE option_name=\"gmt_offset\"";
        $update_option_timezone_string_query = "$update_query_prefix\"$timezone_string_option_value\" WHERE option_name=\"timezone_string\"";

        try {
            $query = "'$update_option_gmt_offset_query'";
            $this->runWpCliCommandWithoutInterruption("$wp_cli_command $query");
            $query = "'$update_option_timezone_string_query'";
            $this->runWpCliCommandWithoutInterruption("$wp_cli_command $query");
        } catch (FailedToRunWpCliCommand $exception) {
            throw new FailedToSetTimezone(
                "Failed to run query $query through WP CLI '$wp_cli_command' command.",
                $exception
            );
        }
    }
}
