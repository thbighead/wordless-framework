<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Mail\Sender\Traits;

use Wordless\Application\Helpers\DirectoryFiles\Exceptions\NotAPhpFile;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Libraries\Mail\Message\Template;
use Wordless\Application\Libraries\Mail\Sender\Traits\Validator\Exceptions\EmptyReceiversList;

trait PrepareForWpMail
{
    /**
     * @return string[]
     */
    private function prepareAttachmentsForWpMail(): array
    {
        return array_keys($this->attachments);
    }

    /**
     * @return string[]
     */
    private function prepareHeadersForWpMail(): array
    {
        $prepared_headers = [];

        foreach ($this->headers as $header_name => $header_value) {
            $prepared_headers[] = "$header_name:$header_value";
        }

        return $prepared_headers;
    }

    /**
     * @return string
     * @throws NotAPhpFile
     * @throws PathNotFoundException
     */
    private function prepareMessageForWpMail(): string
    {
        return $this->message instanceof Template ? $this->message->render() : $this->message;
    }

    /**
     * @return string[]
     * @throws EmptyReceiversList
     */
    private function prepareToForWpMail(): array
    {
        $prepared_to = [];

        foreach ($this->receivers as $receiver_email => $receiver_name) {
            $prepared_to[] = $this->formatEmailReference($receiver_email, $receiver_name);
        }

        return $this->validateTo($prepared_to);
    }
}
