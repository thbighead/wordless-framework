<?php

namespace Wordless\Contracts\Adapter\CustomTaxonomy;

use Wordless\Abstractions\Guessers\CustomTaxonomyNameGuesser;
use Wordless\Exceptions\InvalidCustomTaxonomyName;

trait Register
{
    /**
     * @return void
     * @throws InvalidCustomTaxonomyName
     */
    public static function register()
    {
        if (static::NAME === null) {
            $guesser = new CustomTaxonomyNameGuesser(static::class);
            register_taxonomy(static::$type_key = $guesser->getValue(), self::mountArguments());

            return;
        }

        self::validateTypeKey();

        register_taxonomy(static::$type_key = static::TYPE_KEY, self::mountArguments());
    }
}
