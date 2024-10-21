<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Contracts\IRelatedMetaData\Traits\WithMetaData\Traits\Crud\Traits\Read\Exceptions;

use Exception;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidMetaKey extends Exception
{
    public function __construct(private readonly string $invalid_meta_key, ?Throwable $previous = null)
    {
        parent::__construct(
            "The meta key '$this->invalid_meta_key' is invalid.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
