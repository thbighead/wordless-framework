<?php

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
