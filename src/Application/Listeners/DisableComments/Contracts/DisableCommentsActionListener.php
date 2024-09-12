<?php

namespace Wordless\Application\Listeners\DisableComments\Contracts;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
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
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    public static function areCommentsDisabled(): bool
    {
        return !self::areCommentsEnabled();
    }

    /**
     * @return bool
     * @throws EmptyConfigKey
     * @throws PathNotFoundException
     */
    final public static function areCommentsEnabled(): bool
    {
        return Config::wordpressAdmin(self::CONFIG_KEY_ENABLE_COMMENTS, false);
    }
}
