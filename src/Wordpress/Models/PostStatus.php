<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Wordpress\Models\PostStatus\Enums\StandardStatus;

readonly class PostStatus
{
    public function __construct(public string $name)
    {
    }

    public function is(PostStatus|StandardStatus|string $status): bool
    {
        if (!is_string($status)) {
            $status = $status->value ?? $status->name;
        }

        return $this->name === $status;
    }
}
