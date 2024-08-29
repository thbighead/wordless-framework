<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use InvalidArgumentException;
use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Exceptions\DuplicatedEnqueueableId;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Wordpress\Hook\Enums\Action;

class ResolveAdminEnqueues extends ActionListener
{
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'runEnqueues';

    /**
     * @return void
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     * @throws InvalidArgumentException
     * @throws FormatException
     * @throws EmptyConfigKey
     * @throws DotEnvNotSetException
     * @throws DuplicatedEnqueueableId
     */
    public static function runEnqueues(): void
    {
        Bootstrapper::bootEnqueues(true);
    }

    protected static function hook(): ActionHook
    {
        return Action::admin_enqueue_scripts;
    }
}
