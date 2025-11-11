<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DataCache\Contracts\Subjectable\DTO\ArraySubjectDTO\Traits;

trait Internal
{
    private mixed $value;
    private bool $is_value_set = false;

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
}
