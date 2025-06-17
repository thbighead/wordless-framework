<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use InvalidArgumentException;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Application\Listeners\EnableCors\Exceptions\FailedToEnableCors;
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
     * @throws FailedToEnableCors
     */
    public static function enable(): void
    {
        try {
            if (Environment::get('WORDLESS_CORS', false)) {
                Cors::enable();
            }
        } catch (CannotResolveEnvironmentGet|InvalidArgumentException $exception) {
            throw new FailedToEnableCors($exception);
        }
    }

    protected static function hook(): ActionHook
    {
        return Action::init;
    }
}
