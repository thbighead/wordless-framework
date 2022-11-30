<?php

/**
 * Used just to help IDE to know those constants should be correctly loaded dynamically.
 */

use Wordless\Contracts\IMultipleConstructors;
use Wordless\Contracts\MultipleConstructors;

const INTERNAL_WORDLESS_CACHE = [];

class MarkMultipleConstructorsWithNecessaryConstantsToSuppressLintError implements IMultipleConstructors {
    use MultipleConstructors;

    public static function constructorsDictionary(): array
    {
        return [];
    }
}
