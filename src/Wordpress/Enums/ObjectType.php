<?php declare(strict_types=1);

namespace Wordless\Wordpress\Enums;

enum ObjectType
{
    case comment;
    case network;
    case post;
    case site;
    case term;
    case user;
}
