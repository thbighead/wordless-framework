<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Makers\MakeCustomTaxonomy\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Wordpress\Taxonomy;

class FailedToCreateCustomTaxonomyDictionaryClassFile extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Could not create a new ' . Taxonomy::class . ' dictionary file.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
