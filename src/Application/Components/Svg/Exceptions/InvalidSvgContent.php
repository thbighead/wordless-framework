<?php declare(strict_types=1);

namespace Wordless\Application\Components\Svg\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidSvgContent extends DomainException
{
    public function __construct(readonly public string $svg, ?Throwable $previous = null)
    {
        parent::__construct(
            'This SVG content lacks of SVG tag opening.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
