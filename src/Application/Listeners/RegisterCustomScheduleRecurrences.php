<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Environment\Exceptions\DotEnvNotSetException;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Infrastructure\Wordpress\Schedule\Contracts\RecurrenceInSeconds;
use Wordless\Wordpress\Hook\Enums\Filter;

class RegisterCustomScheduleRecurrences extends FilterListener
{
    /**
     * @param array $schedules
     * @return array
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    public static function register(array $schedules): array
    {
        foreach (Bootstrapper::getInstance()->getProvidedSchedules() as $scheduleClassNamespace) {
            if (($recurrenceInSeconds = $scheduleClassNamespace::recurrence()) instanceof RecurrenceInSeconds
                && !isset($schedules[$recurrenceInSeconds->value])) {
                $schedules[$recurrenceInSeconds->value] = [
                    'interval' => $recurrenceInSeconds->intervalInSeconds(),
                    'display' => esc_html__($recurrenceInSeconds->displayName()),
                ];
            }
        }

        return $schedules;
    }

    protected static function hook(): FilterHook
    {
        return Filter::cron_schedules;
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }
}
