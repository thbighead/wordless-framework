<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits;

trait Internal
{
    public function __construct(string $subject)
    {
        parent::__construct($subject);
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
