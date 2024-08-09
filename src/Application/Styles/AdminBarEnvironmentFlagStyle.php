<?php

namespace Wordless\Application\Styles;

use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;

class AdminBarEnvironmentFlagStyle extends EnqueueableStyle
{
    protected static function relativeFilepath(): string
    {
        return 'public/css/env-flag.min.css';
    }
}
