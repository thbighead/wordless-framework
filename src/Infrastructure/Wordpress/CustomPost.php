<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Repository;
use Wordless\Wordpress\Models\Post\Contracts\BasePost;

abstract class CustomPost extends BasePost
{
    use Repository;
}
