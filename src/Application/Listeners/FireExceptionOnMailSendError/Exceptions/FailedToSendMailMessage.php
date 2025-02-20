<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\FireExceptionOnMailSendError\Exceptions;

use Throwable;
use Wordless\Exceptions\WpErrorException;
use WP_Error;

class FailedToSendMailMessage extends WpErrorException
{
    protected function mountMessage(): string
    {
        return "Failed to send e-mail message due to the following errors: {$this->getErrorMessagesAsString()}";
    }
}
