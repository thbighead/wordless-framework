<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Carbon;

use Carbon\CarbonTimeZone as OriginalCarbonTimeZone;
use Exception;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Timezone;
use Wordless\Application\Libraries\Carbon\CarbonTimeZone\Exceptions\FailedToInstantiateOriginalCarbonTimeZone;
use Wordless\Application\Libraries\Carbon\Contracts\CarbonAdapter;
use Wordless\Exceptions\FailedToRetrieveConfigFromWordpressConfigFile;

/**
 * @mixin OriginalCarbonTimeZone
 */
class CarbonTimeZone extends CarbonAdapter
{
    protected static function originalClassNamespace(): string
    {
        return OriginalCarbonTimeZone::class;
    }

    /**
     * @param OriginalCarbonTimeZone|string|null $timezone
     * @throws FailedToInstantiateOriginalCarbonTimeZone
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
     */
    public function __construct(OriginalCarbonTimeZone|string|null $timezone = null)
    {
        if ($timezone instanceof OriginalCarbonTimeZone) {
            $this->original = $timezone;

            return;
        }

        $timezone ??= Timezone::forPhpIni();

        try {
            $this->original = new OriginalCarbonTimeZone($timezone);
        } catch (Exception $exception) {
            throw new FailedToInstantiateOriginalCarbonTimeZone($timezone, $exception);
        }
    }
}
