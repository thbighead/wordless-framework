<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Carbon;

use Carbon\Carbon as OriginalCarbon;
use Carbon\Exceptions\InvalidFormatException;
use DateTimeInterface;
use DateTimeZone;
use Wordless\Application\Libraries\Carbon\Contracts\CarbonAdapter;

/**
 * @mixin OriginalCarbon
 */
class Carbon extends CarbonAdapter
{
    protected static function originalClassNamespace(): string
    {
        return OriginalCarbon::class;
    }

    /**
     * @param OriginalCarbon|DateTimeInterface|string|null $time
     * @param DateTimeZone|string|null $timezone
     * @throws InvalidFormatException
     */
    public function __construct(
        OriginalCarbon|DateTimeInterface|null|string $time = null,
        DateTimeZone|null|string                     $timezone = null
    )
    {
        if ($time instanceof OriginalCarbon) {
            $this->original = $time;

            if ($timezone !== null) {
                $this->original->setTimezone($timezone);
            }

            return;
        }

        $this->original = new OriginalCarbon($time, $timezone ?? wp_timezone());
    }
}
