<?php

namespace Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO;

use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO\Exceptions\CannotCallOfFromSubject;

class SubjectDTO
{
    private readonly mixed $original_subject;

    public function __construct(protected mixed $subject, protected readonly string $helper_class_namespace)
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
