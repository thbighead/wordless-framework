<?php

namespace Wordless\Application\Helpers\ProjectPath\Contracts;

use Wordless\Application\Helpers\ProjectPath\Contracts\Subjactable\DTO\ProjectPathSubjectDTO;
use Wordless\Infrastructure\Helper\Contracts\Subjectable as BaseSubjectable;

abstract class Subjactable extends BaseSubjectable
{
    /**
     * @param string $subject
     * @return ProjectPathSubjectDTO
     */
    public static function of(mixed $subject): ProjectPathSubjectDTO
    {
        return new ProjectPathSubjectDTO($subject);
    }
}
