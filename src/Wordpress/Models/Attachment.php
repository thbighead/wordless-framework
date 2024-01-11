<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Infrastructure\Wordpress\CustomPost;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;

class Attachment extends CustomPost
{
    protected const TYPE_KEY = StandardType::attachment->name;
}
