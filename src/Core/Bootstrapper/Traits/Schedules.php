<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits;

use Generator;
use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Exceptions\DotEnvNotSetException;

trait Schedules
{
    /**
     * @return void
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     */
    public static function bootIntoRegisterSchedulesCommand(): void
    {
        foreach (self::getInstance()->getProvidedSchedules() as $providedSchedule) {
            $providedSchedule::schedule();
        }
    }

    private function getProvidedSchedules(): Generator
    {
        foreach ($this->loaded_providers as $provider) {
            foreach ($provider->registerSchedules() as $scheduleClassName) {
                yield $scheduleClassName;
            }
        }
    }
}
