<?php

/**
 * Used just to help IDE to know those constants should be correctly loaded dynamically.
 */

use Wordless\Contracts\MultipleConstructors\IMultipleConstructors;
use Wordless\Contracts\MultipleConstructors\Traits\MultipleConstructorsGuesser;

const INTERNAL_WORDLESS_CACHE = [];

class MarkIMultipleConstructorsWithNecessaryConstantsToSuppressLintError implements IMultipleConstructors
{
    use MultipleConstructorsGuesser;

    public static function constructorsDictionary(): array
    {
        return [];
    }
}
