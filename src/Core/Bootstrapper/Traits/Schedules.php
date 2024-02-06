<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits;

use Generator;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;

trait Schedules
{
    /**
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     * @throws EmptyConfigKey
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
