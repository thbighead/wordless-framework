<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Carbon;

use Carbon\CarbonPeriod as OriginalCarbonPeriod;
use DateTimeZone;
use InvalidArgumentException;
use Wordless\Application\Libraries\Carbon\Contracts\CarbonAdapter;

/**
 * @mixin OriginalCarbonPeriod
 */
class CarbonPeriod extends CarbonAdapter
{
    protected static function originalClassNamespace(): string
    {
        return OriginalCarbonPeriod::class;
    }

    /**
     * @param OriginalCarbonPeriod|null $period
     * @param DateTimeZone|null $timezone
     * @param ...$arguments
     * @throws InvalidArgumentException
     */
    public function __construct(?OriginalCarbonPeriod $period = null, ?DateTimeZone $timezone = null, ...$arguments)
    {
        if ($period instanceof OriginalCarbonPeriod) {
            $this->setOriginal($period, $timezone);

            return;
        }

        $this->original = new OriginalCarbonPeriod(...$arguments, $this->resolveTimezone($timezone));
    }
}
