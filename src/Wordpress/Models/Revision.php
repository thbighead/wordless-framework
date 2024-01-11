<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Infrastructure\Wordpress\CustomPost;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;

class Revision extends CustomPost
{
    protected const TYPE_KEY = StandardType::revision->name;
}
