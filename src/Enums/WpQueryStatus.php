<?php declare(strict_types=1);

namespace Wordless\Enums;

/**
 * https://developer.wordpress.org/reference/classes/wp_query/#status-parameters
 */
class WpQueryStatus
{
    public const AUTO = 'auto';
    public const ANY = 'any'; // retrieves any status except for ‘inherit’, ‘trash’ and ‘auto-draft’
    public const DRAFT = 'draft';
    public const FUTURE = 'future';
    public const INHERIT = 'inherit';
    public const PENDING = 'pending';
    public const POST_STATUS_KEY = 'post_status';
    public const PRIVATE = 'private';
    public const PUBLISH = 'publish';
    public const TRASH = 'trash';
    public const TRULY_ANY = 'any,inherit,trash,auto-draft';
}
