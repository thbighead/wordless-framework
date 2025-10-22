<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Create\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Wordpress\Taxonomy;

class FailedToRetrieveNewTermId extends DomainException
{
    public function __construct(
        public readonly string $created_taxonomy_name,
        public readonly array  $insert_result,
        ?Throwable             $previous = null
    )
    {
        parent::__construct(
            'Failed to retrieve the created '
            . Taxonomy::TERM_ID_RESULT_KEY
            . " of $created_taxonomy_name (it maybe created, however).",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
