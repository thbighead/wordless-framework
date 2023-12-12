<?php

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Repository;
use Wordless\Wordpress\Models\Post;

abstract class CustomPost extends Post
{
    use Register;
    use Repository;

    protected const TYPE_KEY = null;
}
