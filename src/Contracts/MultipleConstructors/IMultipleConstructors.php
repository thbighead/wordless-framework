<?php

namespace Wordless\Contracts\MultipleConstructors;

interface IMultipleConstructors
{
    const PARSED_ARGUMENTS_ARGUMENTS_VALUES_KEY = 'arguments_values';
    const PARSED_ARGUMENTS_ARGUMENTS_TYPES_KEY = 'arguments_types';
    const PARSED_ARGUMENTS_NUMBER_OF_ARGUMENTS_KEY = 'number_of_arguments';

    /**
     * Must have the following structure:
     * [
     *      number_of_arguments => [
     *          concatenated_arguments_types => ConstructorNameInPascalCase,
     *          ...
     *      ],
     *      ...
     * ]
     * Example:
     * [
     *      3 => [
     *          'stringdoubleboolean' => '__constructStringFloatBool',
     *          'integerintegerinteger' => '__constructOnlyInts',
     *      ],
     *      1 => [
     *          'Wordless\\Adapters\\WordlessCommand' => '__constructForWordlessCommand',
     *          'array' => 'ForArray',
     *          'integer' => 'ForInteger',
     *      ],
     *      // To avoid any kind of errors use Wordless\Helpers\GetType constants
     *      2 => [
     *          Wordless\Helpers\GetType::INTEGER . Wordless\Helpers\GetType::BOOLEAN => 'WhenNumberAndBool',
     *          Wordless\Helpers\GetType::BOOLEAN . Wordless\Helpers\GetType::BOOLEAN => 'WhenBoolBeforeNumber',
     *      ],
     * ]
     *
     * @return string[]
     * @noinspection SpellCheckingInspection
     */
    public static function constructorsDictionary(): array;
}
