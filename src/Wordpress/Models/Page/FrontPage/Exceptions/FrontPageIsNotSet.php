<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Page\FrontPage\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FrontPageIsNotSet extends DomainException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'There is no page set as custom WordPress home page.',
            ExceptionCode::logic_control->value,
            $previous
        );
    }
}
