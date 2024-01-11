<?php

namespace Wordless\Wordpress\QueryBuilder\Enums;

enum Status: string
{
    case auto = 'auto';
    /** retrieves any status except for ‘inherit’, ‘trash’ and ‘auto-draft’ */
    case any = 'any';
    case draft = 'draft';
    case future = 'future';
    case inherit = 'inherit';
    case pending = 'pending';
    case post_status_key = 'post_status';
    case private = 'private';
    case publish = 'publish';
    case trash = 'trash';
    case truly_any = 'any,inherit,trash,auto-draft';
}
