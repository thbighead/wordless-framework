<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits;

use Generator;
use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Environment\Exceptions\DotEnvNotSetException;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\FailedToLoadBootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\FailedToLoadErrorReportingConfiguration;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Infrastructure\Wordpress\Schedule;

trait Schedules
{
    /**
     * @return void
     * @throws FailedToLoadBootstrapper
     */
    public static function bootIntoRegisterSchedulesCommand(): void
    {
        foreach (self::getInstance()->getProvidedSchedules() as $providedSchedule) {
            $providedSchedule::schedule();
        }
    }

    /**
     * @return Generator<string|Schedule>
     */
    public function getProvidedSchedules(): Generator
    {
        foreach ($this->loaded_providers as $provider) {
            foreach ($provider->registerSchedules() as $scheduleClassName) {
                yield $scheduleClassName;
            }
        }
    }
}
