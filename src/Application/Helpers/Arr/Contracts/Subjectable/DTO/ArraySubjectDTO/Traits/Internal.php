<?php

namespace Wordless\Application\Helpers\Arr\Contracts\Subjectable\DTO\ArraySubjectDTO\Traits;

trait Internal
{
    public function __construct(array $subject)
    {
        parent::__construct($subject);
    }

    /**
     * @return array
     */
    public function getOriginalSubject(): array
    {
        return parent::getOriginalSubject();
    }

    /**
     * @return array
     */
    public function getSubject(): array
    {
        return parent::getSubject();
    }
}
