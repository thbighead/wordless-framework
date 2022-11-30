<?php

namespace Wordless\Contracts;

use Wordless\Exceptions\ClassDoesNotImplementsMultipleConstructors;
use Wordless\Exceptions\ConstructorNotImplemented;
use Wordless\Helpers\GetType;
use Wordless\Helpers\Str;

trait MultipleConstructors
{
    /**
     * @throws ClassDoesNotImplementsMultipleConstructors
     * @throws ConstructorNotImplemented
     */
    public function __construct()
    {
        /** @noinspection PhpInstanceofIsAlwaysTrueInspection */
        if (!($this instanceof IMultipleConstructors)) {
            throw new ClassDoesNotImplementsMultipleConstructors(static::class);
        }

        $constructor_method = self::guessConstructor(self::parseArguments($arguments = func_get_args()));

        if (!method_exists($this, $constructor_method)) {
            throw new ConstructorNotImplemented(static::class, $constructor_method);
        }

        call_user_func_array([$this, $constructor_method], $arguments);
    }

    private static function guessConstructor(array $parsed_arguments): string
    {
        return Str::startWith(static::constructorsDictionary()[
            $parsed_arguments[self::PARSED_ARGUMENTS_NUMBER_OF_ARGUMENTS_KEY]
        ][
            $parsed_arguments[self::PARSED_ARGUMENTS_ARGUMENTS_TYPES_KEY]
        ] ?? $parsed_arguments[self::PARSED_ARGUMENTS_NUMBER_OF_ARGUMENTS_KEY], '__construct');
    }

    private static function parseArguments(array $arguments): array
    {
        $parsed_arguments = [
            self::PARSED_ARGUMENTS_NUMBER_OF_ARGUMENTS_KEY => count($arguments),
            self::PARSED_ARGUMENTS_ARGUMENTS_VALUES_KEY => $arguments,
            self::PARSED_ARGUMENTS_ARGUMENTS_TYPES_KEY => '',
        ];

        foreach ($arguments as $argument) {
            $parsed_arguments[self::PARSED_ARGUMENTS_ARGUMENTS_TYPES_KEY] .= GetType::of($argument);
        }

        return $parsed_arguments;
    }
}
