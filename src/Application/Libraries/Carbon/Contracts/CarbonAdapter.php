<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Carbon\Contracts;

use Carbon\Carbon as OriginalCarbon;
use Carbon\CarbonImmutable as OriginalCarbonImmutable;
use Carbon\CarbonInterval as OriginalCarbonInterval;
use Carbon\CarbonPeriod as OriginalCarbonPeriod;
use Carbon\CarbonPeriodImmutable as OriginalCarbonPeriodImmutable;
use Carbon\CarbonTimeZone as OriginalCarbonTimeZone;
use Carbon\Exceptions\InvalidFormatException;
use DateTimeZone;
use Exception;
use InvalidArgumentException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Timezone;
use Wordless\Application\Libraries\Carbon\Carbon;
use Wordless\Application\Libraries\Carbon\CarbonImmutable;
use Wordless\Application\Libraries\Carbon\CarbonInterval;
use Wordless\Application\Libraries\Carbon\CarbonPeriod;
use Wordless\Application\Libraries\Carbon\CarbonPeriodImmutable;
use Wordless\Application\Libraries\Carbon\CarbonTimeZone;
use Wordless\Application\Libraries\Carbon\CarbonTimeZone\Exceptions\FailedToInstantiateOriginalCarbonTimeZone;
use Wordless\Application\Libraries\Carbon\Contracts\CarbonAdapter\Exceptions\FailedToAdaptFromOriginalCarbonClass;
use Wordless\Exceptions\FailedToRetrieveConfigFromWordpressConfigFile;

abstract class CarbonAdapter
{
    abstract protected static function originalClassNamespace(): string;

    protected object $original;

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws EmptyConfigKey
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     * @throws Exception
     */
    public static function __callStatic(string $name, array $arguments): mixed
    {
        if ((new OriginalCarbonTimeZone($timezone = Timezone::forPhpIni()))->toOffsetName() !== wp_timezone_string()) {
            date_default_timezone_set($timezone);
        }

        return static::createFromOriginalCarbon(
            static::originalClassNamespace()::$name(...self::parseSelfArguments($arguments))
        );
    }

    /**
     * @param mixed $originalCarbon
     * @param DateTimeZone|string|null $timezone
     * @return mixed
     * @throws FailedToAdaptFromOriginalCarbonClass
     */
    public static function createFromOriginalCarbon(
        mixed                    $originalCarbon,
        DateTimeZone|null|string $timezone = null
    ): mixed
    {
        if (!is_object($originalCarbon)) {
            return $originalCarbon;
        }

        try {
            return match ($originalCarbon::class) {
                OriginalCarbon::class => new Carbon($originalCarbon, $timezone),
                OriginalCarbonImmutable::class => new CarbonImmutable($originalCarbon, $timezone),
                OriginalCarbonInterval::class => new CarbonInterval($originalCarbon, timezone: $timezone),
                OriginalCarbonPeriod::class => new CarbonPeriod($originalCarbon, $timezone),
                OriginalCarbonPeriodImmutable::class => new CarbonPeriodImmutable($originalCarbon, $timezone),
                OriginalCarbonTimeZone::class => new CarbonTimeZone($originalCarbon),
                default => $originalCarbon,
            };
        } catch (Exception $exception) {
            throw new FailedToAdaptFromOriginalCarbonClass($originalCarbon::class, $exception);
        }
    }

    private static function parseSelfArguments(array $arguments): array
    {
        foreach ($arguments as &$argument) {
            if ($argument instanceof self) {
                $argument = $argument->original;
            }
        }

        return $arguments;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws FailedToAdaptFromOriginalCarbonClass
     */
    public function __call(string $name, array $arguments): mixed
    {
        $originalResult = $this->original->$name(...self::parseSelfArguments($arguments));

        if (!is_object($originalResult)) {
            return $originalResult;
        }

        if ($originalResult instanceof $this->original) {
            $this->original = $originalResult;

            return $this;
        }

        return static::createFromOriginalCarbon($originalResult);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws FailedToAdaptFromOriginalCarbonClass
     */
    public function __get(string $name): mixed
    {
        $originalProperty = $this->original->$name;

        if (!is_object($originalProperty)) {
            return $originalProperty;
        }

        if ($originalProperty instanceof $this->original) {
            $this->original = $originalProperty;

            return $this;
        }

        return static::createFromOriginalCarbon($originalProperty);
    }

    protected function setOriginal(object $original, DateTimeZone|null|string $timezone): void
    {
        $this->original = $original;

        $this->original->setTimezone($this->resolveTimezone($timezone));
    }

    final protected function resolveTimezone(DateTimeZone|null|string $timezone): DateTimeZone|string
    {
        return $timezone ?? wp_timezone();
    }
}
