<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use WP_Error;
use WP_Term;

class FailedToGetTermLink extends DomainException
{
    public function __construct(
        public readonly WP_Term $term,
        public readonly mixed   $wp_function_result,
        ?Throwable              $previous = null
    )
    {
        parent::__construct($this->mountMessage(), ExceptionCode::intentional_interrupt->value, $previous);
    }

    private function mountMessage(): string
    {
        $message =
            "Failed to retrieve the {$this->term->taxonomy} {$this->term->name} (ID: {$this->term->term_id}) url.";

        if ($this->wp_function_result instanceof WP_Error) {
            $message .= ' ' . implode('. ', $this->wp_function_result->get_error_messages());
        }

        return $message;
    }
}
