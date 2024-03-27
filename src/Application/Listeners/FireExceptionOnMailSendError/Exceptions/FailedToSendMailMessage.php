<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\FireExceptionOnMailSendError\Exceptions;

use ErrorException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use WP_Error;

class FailedToSendMailMessage extends ErrorException
{
    public function __construct(public readonly WP_Error $error, ?Throwable $previous = null)
    {
        parent::__construct(
            "Failed to send e-mail message due to the following error: {$this->error->get_error_message()}",
            ExceptionCode::intentional_interrupt->value,
            previous: $previous
        );
    }

    public function getFailedMailData():array
    {
        return $this->error->get_all_error_data();
    }
}
