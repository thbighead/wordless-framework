<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Traits;

use Wordless\Application\Helpers\Str;

trait Internal
{
    public function __construct(string $subject)
    {
        parent::__construct($subject);
    }

    public function getOriginalSubject(): string
    {
        return parent::getOriginalSubject();
    }

    public function getSubject(): string
    {
        return parent::getSubject();
    }

    private function getUpdatedSubject(?string $key): string
    {
        return $key === null ? $this->subject : Str::finishWith($this->subject, ".$key");
    }
}
