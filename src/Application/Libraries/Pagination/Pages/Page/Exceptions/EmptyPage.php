<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Pagination\Pages\Page\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class EmptyPage extends DomainException
{
    public function __construct(public readonly int $page_index, ?Throwable $previous = null)
    {
        parent::__construct(
            "The index $this->page_index leads to an empty page. This probably means that this page does not exists.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
