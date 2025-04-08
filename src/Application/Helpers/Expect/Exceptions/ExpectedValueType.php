<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Expect\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Application\Helpers\GetType;
use Wordless\Infrastructure\Enums\ExceptionCode;

class ExpectedValueType extends InvalidArgumentException
{
    readonly public string $wrong_type;

    public function __construct(
        readonly public mixed  $value_with_wrong_type,
        readonly public string $expected_type,
        ?Throwable             $previous = null
    )
    {
        $this->wrong_type = GetType::of($this->value_with_wrong_type);

        parent::__construct(
            "Expected type $this->expected_type, got $this->wrong_type.",
            ExceptionCode::logic_control->value,
            $previous
        );
    }
}
