<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\DisableComments\Contracts;

use Wordless\Application\Helpers\Config;
use Wordless\Exceptions\FailedToRetrieveConfigFromWordpressConfigFile;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;

abstract class DisableCommentsActionListener extends ActionListener
{
    final public const CONFIG_KEY_ENABLE_COMMENTS = 'enable_comments';

    public static function priority(): int
    {
        return 1;
    }

    /**
     * @return bool
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
     */
    public static function areCommentsDisabled(): bool
    {
        return !self::areCommentsEnabled();
    }

    /**
     * @return bool
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
     */
    final public static function areCommentsEnabled(): bool
    {
        return Config::wordpressAdmin(self::CONFIG_KEY_ENABLE_COMMENTS, false);
    }
}
