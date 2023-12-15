<?php

namespace Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO;

use Wordless\Infrastructure\Helper\Contracts\Subjectable;

class SubjectDTO
{
    private readonly mixed $original_subject;

    public function __construct(private mixed $subject, private readonly string $helper_class_namespace)
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

    final public function __call(string $name, array $arguments)
    {
        return $this->helper_class_namespace::$name($this->subject, ...$arguments);
    }
}
