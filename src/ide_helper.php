<?php

/**
 * Used just to help IDE to know those constants should be correctly loaded dynamically.
 */

use Wordless\Contracts\MultipleConstructors;
use Wordless\Contracts\MultipleConstructorsGuesser;

const INTERNAL_WORDLESS_CACHE = [];

class MarkMultipleConstructorsWithNecessaryConstantsToSuppressLintError implements MultipleConstructors
{
    use MultipleConstructorsGuesser;

    public static function constructorsDictionary(): array
    {
        return [];
    }
}
