<?php

namespace Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO;

class SubjectDTO
{
    private readonly mixed $original_subject;

    public function __construct(protected mixed $subject)
    {
        $this->original_subject = $this->subject;
    }

    public function getOriginalSubject(): mixed
    {
        return $this->original_subject;
    }

    public function getSubject(): mixed
    {
        return $this->subject;
    }
}
