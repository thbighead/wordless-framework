<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

readonly class PostStatus
{
    public function __construct(public string $name)
    {
    }
}
