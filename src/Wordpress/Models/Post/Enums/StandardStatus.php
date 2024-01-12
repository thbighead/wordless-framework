<?php

namespace Wordless\Wordpress\Models\Post\Enums;

enum StandardStatus: string
{
    public const TRULY_ANY = 'any,inherit,trash,auto-draft';
    /** retrieves any status except for ‘inherit’, ‘trash’ and ‘auto-draft’ */
    public const ANY = 'any';

    case auto = 'auto-draft';
    case draft = 'draft';
    case future = 'future';
    case inherit = 'inherit';
    case pending = 'pending';
    case private = 'private';
    case publish = 'publish';
    case trash = 'trash';
}
