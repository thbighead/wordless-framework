<?php declare(strict_types=1);

namespace Wordless\Application\Listeners\FireExceptionOnMailSendError\Exceptions;

use Wordless\Exceptions\WpErrorException;

class FailedToSendMailMessage extends WpErrorException
{
    protected function mountMessage(): string
    {
        return "Failed to send e-mail message due to the following errors: {$this->getErrorMessagesAsString()}";
    }
}
