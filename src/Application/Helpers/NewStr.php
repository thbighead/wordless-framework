<?php

namespace Wordless\Application\Helpers;

use Wordless\Application\Helpers\Str\Traits\Internal;
use Wordless\Application\Helpers\Str\Traits\WordCase;

class NewStr extends Str
{
    use Internal;
    use WordCase;

    public static function unaccented(string $string)
    {
        return self::getInflector()->unaccent($string);
    }
}
