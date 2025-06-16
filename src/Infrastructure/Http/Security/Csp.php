<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Http\Security;

use Exception;
use ParagonIE\CSPBuilder\CSPBuilder;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Traits\Internal\Exceptions\FailedToLoadConfigFile;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Libraries\DesignPattern\Singleton;
use Wordless\Exceptions\FailedToRetrieveConfigFromWordlessConfigFile;
use Wordless\Infrastructure\Http\Security\Csp\Exceptions\FailedToSentCspHeadersFromBuilder;

final class Csp extends Singleton
{
    public const CONFIG_KEY = 'csp';

    /**
     * @return void
     * @throws EmptyConfigKey
     * @throws FailedToSentCspHeadersFromBuilder
     * @throws PathNotFoundException
     */
    public static function enable(): void
    {
        self::getInstance()
            ->forcePhpIniCookiesSettings()
            ->sendHeaders();
    }

    private function addCommonCspHeaders(): self
    {
        header('Referrer-Policy: no-referrer-when-downgrade');
        header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');

        return $this;
    }

    /**
     * @return void
     * @throws FailedToRetrieveConfigFromWordlessConfigFile
     * @throws FailedToSentCspHeadersFromBuilder
     */
    private function addConfiguredCspHeaders(): void
    {
        try {
            $cspBuilder = CSPBuilder::fromArray(Config::wordlessCsp()->get());
        } catch (EmptyConfigKey|FailedToLoadConfigFile $exception) {
            throw new FailedToRetrieveConfigFromWordlessConfigFile(self::CONFIG_KEY, previous: $exception);
        }

        try {
            $cspBuilder->sendCSPHeader();
        } catch (Exception $exception) {
            throw new FailedToSentCspHeadersFromBuilder($exception);
        }
    }

    /**
     * @noinspection PhpUsageOfSilenceOperatorInspection
     * @return self
     */
    private function forcePhpIniCookiesSettings(): self
    {
        @ini_set('session.cookie_httponly', true);
        @ini_set('session.cookie_secure', true);
        @ini_set('session.use_only_cookies', true);

        return $this;
    }

    /**
     * @return void
     * @throws EmptyConfigKey
     * @throws FailedToSentCspHeadersFromBuilder
     * @throws PathNotFoundException
     */
    private function sendHeaders(): void
    {
        if (!headers_sent()) {
            $this->addCommonCspHeaders()
                ->addConfiguredCspHeaders();
        }
    }
}
