<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Commands\Traits\RunWpCliCommand\Exceptions\WpCliCommandReturnedNonZero;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Timezone;
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
     * @throws CommandNotFoundException
     * @throws EmptyConfigKey
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     * @throws WpCliCommandReturnedNonZero
     */
    protected function runIt(): int
    {
        $dateConfig = Config::wordpress()->ofKey(self::CONFIG_KEY_ADMIN_DATETIME);

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

        return Command::SUCCESS;
    }

    /**
     * @return void
     * @throws CommandNotFoundException
     * @throws EmptyConfigKey
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     * @throws WpCliCommandReturnedNonZero
     */
    private function setTimezone(): void
    {
        $option_timezone_string = Timezone::forOptionTimezoneString();
        $option_gmt_offset = Timezone::forOptionGmtOffset();

        $this->runWpCliCommand("option update gmt_offset '$option_gmt_offset'");
        $this->runWpCliCommand("option update timezone_string '$option_timezone_string'");
    }
}
