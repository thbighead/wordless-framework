<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Makers\MakeCustomTaxonomy\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Wordpress\Taxonomy;

class FailedToMountCustomTaxonomyClassName extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Could not mount a correct name for a new '
            . Taxonomy::class
            . ' class.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
