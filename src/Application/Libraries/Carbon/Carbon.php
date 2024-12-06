<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Carbon;

use Carbon\Carbon as OriginalCarbon;
use Carbon\CarbonTimeZone;
use Carbon\Exceptions\InvalidFormatException;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Timezone;

/**
 * @mixin OriginalCarbon
 */
class Carbon
{
    private OriginalCarbon $original;

    /**
     * @param DateTimeInterface|string|null $time
     * @param DateTimeZone|string|null $timezone
     * @throws InvalidFormatException
     */
    public function __construct(DateTimeInterface|null|string $time = null, DateTimeZone|null|string $timezone = null)
    {
        $this->original = new OriginalCarbon($time, $timezone ?? wp_timezone());
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

        return OriginalCarbon::$name(...$arguments);
    }
}
