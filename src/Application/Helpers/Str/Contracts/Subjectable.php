<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts;

use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO;
use Wordless\Infrastructure\Helper\Contracts\Subjectable as BaseSubjectable;

abstract class Subjectable extends BaseSubjectable
{
    /**
     * @param string $subject
     * @return StringSubjectDTO
     */
    public static function of(mixed $subject): StringSubjectDTO
    {
        return new StringSubjectDTO($subject);
    }
}
