<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Carbon;

use Carbon\CarbonPeriodImmutable as OriginalCarbonPeriodImmutable;
use DateTimeZone;
use InvalidArgumentException;
use Wordless\Application\Libraries\Carbon\Contracts\CarbonAdapter;

/**
 * @mixin OriginalCarbonPeriodImmutable
 */
class CarbonPeriodImmutable extends CarbonAdapter
{
    protected static function originalClassNamespace(): string
    {
        return OriginalCarbonPeriodImmutable::class;
    }

    /**
     * @param OriginalCarbonPeriodImmutable|null $period
     * @param DateTimeZone|null $timezone
     * @param ...$arguments
     * @throws InvalidArgumentException
     */
    public function __construct(
        ?OriginalCarbonPeriodImmutable $period = null,
        ?DateTimeZone                  $timezone = null,
                                       ...$arguments
    )
    {
        if ($period instanceof OriginalCarbonPeriodImmutable) {
            $this->setOriginal($period, $timezone);

            return;
        }

        $this->original = new OriginalCarbonPeriodImmutable(...$arguments, $this->resolveTimezone($timezone));
    }
}
