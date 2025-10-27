<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Wordpress\Models\Post\Contracts\BasePost;
use Wordless\Wordpress\Models\Post\Traits\FeaturedImage;

class Post extends BasePost
{
    use FeaturedImage;
}
