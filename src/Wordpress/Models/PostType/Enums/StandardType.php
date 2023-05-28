<?php

namespace Wordless\Wordpress\Models\PostType\Enums;

enum StandardType
{
    final public const ANY = 'any';

    case attachment;
    case page;
    case post;
    case revision;
}
