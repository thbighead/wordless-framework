<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\PolymorphicConstructor\Traits;

use Wordless\Application\Helpers\GetType;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Libraries\PolymorphicConstructor\Contracts\IPolymorphicConstructor;
use Wordless\Application\Libraries\PolymorphicConstructor\Exceptions\ClassDoesNotImplementsPolymorphicConstructor;
use Wordless\Application\Libraries\PolymorphicConstructor\Exceptions\ConstructorNotImplemented;
use Wordless\Application\Libraries\PolymorphicConstructor\Traits\PolymorphicConstructorGuesser\DTO\ParsedArgumentsDTO;
use Wordless\Application\Libraries\PolymorphicConstructor\Traits\PolymorphicConstructorGuesser\Exceptions\FailedToGuessConstructor;

trait PolymorphicConstructorGuesser
{
    /**
     * @throws ClassDoesNotImplementsPolymorphicConstructor
     * @throws ConstructorNotImplemented
     * @throws FailedToGuessConstructor
     */
    public function __construct()
    {
        /** @noinspection PhpInstanceofIsAlwaysTrueInspection */
        if (!($this instanceof IPolymorphicConstructor)) {
            throw new ClassDoesNotImplementsPolymorphicConstructor(static::class);
        }

        $constructor_method = self::guessConstructor(new ParsedArgumentsDTO($arguments = func_get_args()));

        if (!method_exists($this, $constructor_method)) {
            throw new ConstructorNotImplemented(static::class, $constructor_method);
        }

        call_user_func_array([$this, $constructor_method], $arguments);
    }

    /**
     * @param ParsedArgumentsDTO $parsedArguments
     * @return string
     * @throws FailedToGuessConstructor
     */
    private static function guessConstructor(ParsedArgumentsDTO $parsedArguments): string
    {
        $constructor_method = null;

        foreach ($parsedArguments->typesConcat() as $types_concat) {
            $constructor_method = static::constructorsDictionary()[$parsedArguments->count][$types_concat] ?? null;

            if ($constructor_method !== null) {
                break;
            }
        }

        if ($constructor_method === null) {
            throw new FailedToGuessConstructor($parsedArguments, static::constructorsDictionary());
        }

        return Str::startWith($constructor_method, '__construct');
    }
}
