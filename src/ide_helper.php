<?php

/**
 * Used just to help IDE to know those constants should be correctly loaded dynamically.
 */

use Wordless\Application\Libraries\PolymorphicConstructor\Contracts\IPolymorphicConstructor;
use Wordless\Contracts\MultipleConstructors\Traits\MultipleConstructorsGuesser;

const INTERNAL_WORDLESS_CACHE = [];

class MarkIPolymorphicConstructorWithNecessaryConstantsToSuppressLintError implements IPolymorphicConstructor
{
    use MultipleConstructorsGuesser;

    public static function constructorsDictionary(): array
    {
        return [];
    }
}
