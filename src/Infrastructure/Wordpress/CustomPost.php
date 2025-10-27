<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Repository;
use Wordless\Wordpress\Models\Post\Contracts\BasePost;

abstract class CustomPost extends BasePost
{
    use Register;
    use Repository;

    protected const TYPE_KEY = null;
}
