<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Mail\Sender\Traits;

use Wordless\Application\Libraries\Mail\Sender\Traits\Validator\Exceptions\EmptyReceiversList;
use Wordless\Application\Libraries\Mail\Sender\Traits\Validator\Exceptions\EmptySubject;

trait Validator
{
    /**
     * @return string
     * @throws EmptySubject
     */
    private function validateSubject(): string
    {
        if (empty($this->subject)) {
            throw new EmptySubject;
        }

        return $this->subject;
    }

    /**
     * @param string[] $to
     * @return string[]
     * @throws EmptyReceiversList
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function validateTo(array $to): array
    {
        if (empty($to)) {
            throw new EmptyReceiversList($this->receivers);
        }

        return $to;
    }
}
