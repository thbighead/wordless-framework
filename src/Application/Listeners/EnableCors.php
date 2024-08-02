<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Environment;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Http\Security\Cors;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class EnableCors extends ActionListener
{
    /**
     * The public static method which shall be executed during hook.
     */
    protected const FUNCTION = 'enable';

    /**
     * @return void
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
    public static function enable(): void
    {
        if (Environment::get('WORDLESS_CORS', false)) {
            Cors::enable();
        }
    }

    protected static function hook(): ActionHook
    {
        return Action::init;
    }
}
