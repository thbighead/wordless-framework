<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Arr\Contracts;

use Wordless\Application\Helpers\Arr\Contracts\Subjectable\DTO\ArraySubjectDTO;
use Wordless\Infrastructure\Helper\Contracts\Subjectable as BaseSubjectable;

abstract class Subjectable extends BaseSubjectable
{
    /**
     * @param array $subject
     * @return ArraySubjectDTO
     */
    public static function of(mixed $subject): ArraySubjectDTO
    {
        return new ArraySubjectDTO($subject);
    }
}
