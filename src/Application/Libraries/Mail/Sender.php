<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Mail;

use Wordless\Application\Helpers\DirectoryFiles\Exceptions\NotAPhpFile;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Libraries\Mail\Message\Template;
use Wordless\Application\Libraries\Mail\Sender\Traits\PrepareForWpMail;
use Wordless\Application\Libraries\Mail\Sender\Traits\Setters;
use Wordless\Application\Libraries\Mail\Sender\Traits\Validator;
use Wordless\Application\Libraries\Mail\Sender\Traits\Validator\Exceptions\EmptyReceiversList;
use Wordless\Application\Libraries\Mail\Sender\Traits\Validator\Exceptions\EmptySubject;
use Wordless\Application\Listeners\FireExceptionOnMailSendError;

class Sender
{
    use PrepareForWpMail;
    use Setters;
    use Validator;

    /** @var array<string, string> $attachments */
    private array $attachments = [];
    /** @var array<string, string> $headers */
    private array $headers = [];
    /** @var array<string, string|null> $receivers */
    private array $receivers = [];

    public function __construct(private readonly string $subject, private readonly string|Template $message)
    {
    }

    /**
     * @return void
     * @throws EmptyReceiversList
     * @throws EmptySubject
     * @throws NotAPhpFile
     * @throws PathNotFoundException
     */
    public function send(): void
    {
        FireExceptionOnMailSendError::hookIt();

        wp_mail(
            $this->prepareToForWpMail(),
            $this->validateSubject(),
            $this->prepareMessageForWpMail(),
            $this->prepareHeadersForWpMail(),
            $this->prepareAttachmentsForWpMail()
        );
    }

    private function formatEmailReference(string $email, ?string $email_owner_name = null): string
    {
        return is_null($email_owner_name) ? $email : "$email_owner_name <$email>";
    }
}
