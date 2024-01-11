<?php declare(strict_types=1);

namespace Wordless\Application\Commands\WpHooksList\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Hook\Enums\Type;

class InvalidHookType extends DomainException
{
    public function __construct(public readonly string $invalid_hook_type, ?Throwable $previous = null)
    {
        parent::__construct(
            'Hook types must be one of ' . Type::casesListAsString() . ", '$this->invalid_hook_type' given.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
