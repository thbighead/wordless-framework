<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\PolymorphicConstructor\Traits\PolymorphicConstructorGuesser\Exceptions;

use DomainException;
use Throwable;
use Wordless\Application\Libraries\PolymorphicConstructor\Traits\PolymorphicConstructorGuesser\DTO\ParsedArgumentsDTO;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToGuessConstructor extends DomainException
{
    public function __construct(
        public readonly ParsedArgumentsDTO $parsedArguments,
        public readonly array              $constructors_dictionary,
        ?Throwable                         $previous = null
    )
    {
        parent::__construct(
            "Couldn't guess what constructor to use from dictionary with {$this->parsedArguments->count} arguments typed as "
            . implode(', ', $this->parsedArguments->types)
            . ' respectively.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
