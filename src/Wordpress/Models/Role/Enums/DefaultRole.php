<?php

namespace Wordless\Wordpress\Models\Role\Enums;

enum DefaultRole: string
{
    case admin = 'administrator';
    case author = 'author';
    case contributor = 'contributor';
    case editor = 'editor';
    case subscriber = 'subscriber';
}
