<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\PostType\Enums;

use Wordless\Wordpress\Models\PostType;

enum StandardType
{
    final public const ANY = 'any';

    case attachment;
    case page;
    case post;
    case revision;

    public function is(PostType|StandardType|string $type): bool
    {
        if (!is_string($type)) {
            $type = $type->name;
        }

        return $this->name === $type;
    }
}
