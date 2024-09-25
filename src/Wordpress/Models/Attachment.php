<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Wordpress\Models\PostType\Enums\StandardType;

class Attachment extends Post
{
    protected const TYPE_KEY = StandardType::attachment->name;
}
