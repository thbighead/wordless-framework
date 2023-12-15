<?php

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO;

use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;

class StringSubjectDTO extends SubjectDTO
{
    public function __construct(string $subject)
    {
        parent::__construct($subject, Str::class);
    }

    /**
     * @return string
     */
    public function getOriginalSubject(): string
    {
        return parent::getOriginalSubject();
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return parent::getSubject();
    }

    public function __toString(): string
    {
        return $this->getSubject();
    }
}
