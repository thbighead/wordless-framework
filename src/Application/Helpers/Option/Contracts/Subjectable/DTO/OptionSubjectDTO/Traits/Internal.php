<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Option\Contracts\Subjectable\DTO\OptionSubjectDTO\Traits;

trait Internal
{
    public function __construct(string $subject)
    {
        parent::__construct($subject);
    }
}
