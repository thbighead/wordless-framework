<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders\TaxonomyTermsSeeder\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToGenerateTaxonomyTerms extends RuntimeException
{
    public function __construct(public readonly string $taxonomy, ?Throwable $previous = null)
    {
        parent::__construct(
            "Could not generate terms of taxonomy $this->taxonomy correctly.",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
