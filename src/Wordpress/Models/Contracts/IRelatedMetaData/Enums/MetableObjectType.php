<?php

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Enums;

enum MetableObjectType
{
    case post;
    case comment;
    case term;
    case user;
}
