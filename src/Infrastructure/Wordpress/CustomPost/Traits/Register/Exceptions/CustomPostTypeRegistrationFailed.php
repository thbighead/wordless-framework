<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use WP_Error;

class CustomPostTypeRegistrationFailed extends ErrorException
{
    public function __construct(private readonly WP_Error $wpError, ?Throwable $previous = null)
    {
        parent::__construct(
            implode('; ', $this->wpError->get_error_messages()),
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }

    public function getWpError(): WP_Error
    {
        return $this->wpError;
    }
}
