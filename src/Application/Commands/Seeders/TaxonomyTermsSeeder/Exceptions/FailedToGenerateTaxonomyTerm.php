<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders\TaxonomyTermsSeeder\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToGenerateTaxonomyTerm extends RuntimeException
{
    public function __construct(
        public readonly string  $taxonomy,
        public readonly ?string $term,
        ?Throwable              $previous = null
    )
    {
        parent::__construct($this->mountMessage(), ExceptionCode::intentional_interrupt->value, $previous);
    }

    private function mountMessage(): string
    {
        $message = "Could not generate a taxonomy $this->taxonomy term";

        if ($this->term !== null) {
            $message .= " with slug $this->term";
        }

        return "$message.";
    }
}
