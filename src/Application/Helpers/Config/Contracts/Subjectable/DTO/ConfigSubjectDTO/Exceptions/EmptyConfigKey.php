<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class EmptyConfigKey extends DomainException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Can\'t access an empty config key.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
