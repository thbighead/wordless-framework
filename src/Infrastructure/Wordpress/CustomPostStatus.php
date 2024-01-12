<?php

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Infrastructure\Wordpress\CustomPostStatus\Traits\Register;
use Wordless\Wordpress\Models\PostStatus;

readonly class CustomPostStatus extends PostStatus
{
    use Register;

    protected const NAME = null;
}
