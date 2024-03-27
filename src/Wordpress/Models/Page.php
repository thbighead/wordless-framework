<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Wordpress\Models\PostType\Enums\StandardType;

class Page extends Post
{
    final protected const TYPE_KEY = StandardType::page->name;
}
