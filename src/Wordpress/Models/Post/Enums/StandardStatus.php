<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Enums;

enum StandardStatus: string
{
    public const ANY = 'any'; // retrieves any status except for ‘inherit’, ‘trash’ and ‘auto-draft’ (and it seems that 'pending' is also not included)
    public const REALLY_ANY = [
        self::ANY,
        self::inherit->value,
        self::trash->value,
        self::auto->value,
        self::pending->value,
    ];

    case auto = 'auto-draft';
    case draft = 'draft';
    case future = 'future';
    case inherit = 'inherit';
    case pending = 'pending';
    case private = 'private';
    case publish = 'publish';
    case trash = 'trash';

    public static function reallyAny(): string
    {
        return implode(',', self::REALLY_ANY);
    }
}
