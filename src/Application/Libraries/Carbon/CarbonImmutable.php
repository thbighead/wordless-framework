<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Carbon;

use Carbon\Carbonimmutable as OriginalCarbonImmutable;
use Carbon\Exceptions\InvalidFormatException;
use DateTimeInterface;
use DateTimeZone;
use Wordless\Application\Libraries\Carbon\Contracts\CarbonAdapter;

/**
 * @mixin OriginalCarbonImmutable
 */
class CarbonImmutable extends CarbonAdapter
{
    protected static function originalClassNamespace(): string
    {
        return OriginalCarbonImmutable::class;
    }

    /**
     * @param OriginalCarbonImmutable|DateTimeInterface|string|null $time
     * @param DateTimeZone|string|null $timezone
     * @throws InvalidFormatException
     */
    public function __construct(
        OriginalCarbonImmutable|DateTimeInterface|null|string $time = null,
        DateTimeZone|null|string                              $timezone = null
    )
    {
        if ($time instanceof OriginalCarbonImmutable) {
            $this->original = $time;

            if ($timezone !== null) {
                $this->original->setTimezone($timezone);
            }

            return;
        }

        $this->original = new OriginalCarbonImmutable($time, $timezone ?? wp_timezone());
    }
}
