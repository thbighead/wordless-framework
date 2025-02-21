<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset\Enums;

use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Contracts\Context;

enum StandardContext implements Context
{
    case admin;
    case frontend;
    case no_context;
}
