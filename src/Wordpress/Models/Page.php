<?php

namespace Wordless\Wordpress\Models;

use Wordless\Infrastructure\Wordpress\CustomPost;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;

class Page extends CustomPost
{
    protected const TYPE_KEY = StandardType::page->name;
}
