<?php

namespace Wordless\Application\Libraries\Carbon;

use Carbon\Carbonimmutable as OriginalCarbonImmutable;
use Carbon\CarbonTimeZone;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Timezone;

/**
 * @mixin OriginalCarbonImmutable
 */
class CarbonImmutable
{
    private OriginalCarbonImmutable $original;

    /**
     * @param DateTimeInterface|string|null $time
     * @param DateTimeZone|string|null $timezone
     */
    public function __construct(DateTimeInterface|null|string $time = null, DateTimeZone|null|string $timezone = null)
    {
        $this->original = new OriginalCarbonImmutable($time, $timezone ?? wp_timezone());
    }

    public function __call(string $name, array $arguments)
    {
        return $this->original->$name(...$arguments);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws PathNotFoundException
     * @throws Exception
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if ((new CarbonTimeZone(Timezone::forPhpIni()))->getName() !== $timezone = wp_timezone_string()) {
            date_default_timezone_set($timezone);
        }

        return OriginalCarbonImmutable::$name(...$arguments);
    }
}
