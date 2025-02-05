<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Dotenv\Exception\PathException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Http\Security\Csp;
use Wordless\Infrastructure\Http\Security\Csp\Exceptions\FailedToSentCspHeadersFromBuilder;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class EnableCsp extends ActionListener
{
    /**
     * The public static method which shall be executed during hook.
     */
    protected const FUNCTION = 'enable';

    /**
     * Solving insecure cookies (https://rainastudio.com/enable-secure-cookie-setting/)
     *
     * @return void
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FailedToSentCspHeadersFromBuilder
     * @throws FormatException
     * @throws PathException
     * @throws PathNotFoundException
     */
    public static function enable(): void
    {
        if (Environment::get('WORDLESS_CSP', false)) {
            Csp::enable();
        }
    }

    protected static function hook(): ActionHook
    {
        return Action::init;
    }
}
