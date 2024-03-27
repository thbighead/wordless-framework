<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Mail\Sender\Traits;

use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

trait Setters
{
    /**
     * @param string $attachment_file_path
     * @return void
     * @throws PathNotFoundException
     */
    public function setAttachment(string $attachment_file_path): void
    {
        $absolute_attachment_file_path = ProjectPath::realpath($attachment_file_path);

        $this->attachments[$absolute_attachment_file_path] = $absolute_attachment_file_path;
    }

    public function setHeader(string $header_name, string $header_value): static
    {
        $this->headers[$header_name] = $header_value;

        return $this;
    }

    public function setHeaderFrom(string $email, ?string $name = null): static
    {
        $this->setHeader('From', $this->formatEmailReference($email, $name));

        return $this;
    }

    public function setReceiver(string $email, ?string $receiver_name = null): static
    {
        $this->receivers[$email] = $receiver_name;

        return $this;
    }
}
