<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Exceptions;

use JsonException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class JsonDecodeError extends JsonException
{
    public function __construct(public readonly string $json, ?Throwable $previous = null)
    {
        parent::__construct(
            "Could not decode '$this->json' as a JSON.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
