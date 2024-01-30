<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Arr\Contracts\Subjectable\DTO\ArraySubjectDTO\Traits;

trait Internal
{
    public function __construct(array $subject)
    {
        parent::__construct($subject);
    }

    public function getOriginalSubject(): array
    {
        return parent::getOriginalSubject();
    }

    public function getSubject(): array
    {
        return parent::getSubject();
    }
}
