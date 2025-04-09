<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Carbon;

use Carbon\CarbonInterval as OriginalCarbonInterval;
use Closure;
use DateInterval;
use DateTimeZone;
use Exception;
use Wordless\Application\Libraries\Carbon\Contracts\CarbonAdapter;

/**
 * @mixin OriginalCarbonInterval
 */
class CarbonInterval extends CarbonAdapter
{
    protected static function originalClassNamespace(): string
    {
        return OriginalCarbonInterval::class;
    }

    /**
     * @param OriginalCarbonInterval|Closure|DateInterval|int|string|null $years
     * @param float|int|null $months
     * @param float|int|null $weeks
     * @param float|int|null $days
     * @param float|int|null $hours
     * @param float|int|null $minutes
     * @param float|int|null $seconds
     * @param float|int|null $microseconds
     * @param DateTimeZone|string|null $timezone
     * @throws Exception
     */
    public function __construct(
        OriginalCarbonInterval|Closure|DateInterval|int|null|string $years = 1,
        float|int|null                                              $months = null,
        float|int|null                                              $weeks = null,
        float|int|null                                              $days = null,
        float|int|null                                              $hours = null,
        float|int|null                                              $minutes = null,
        float|int|null                                              $seconds = null,
        float|int|null                                              $microseconds = null,
        DateTimeZone|null|string                                    $timezone = null
    )
    {
        if ($years instanceof OriginalCarbonInterval) {
            $this->setOriginal($years, $timezone);

            return;
        }

        $this->original = (new OriginalCarbonInterval(
            $years,
            $months,
            $weeks,
            $days,
            $hours,
            $minutes,
            $seconds,
            $microseconds
        ))->setTimezone($this->resolveTimezone($timezone));
    }
}
