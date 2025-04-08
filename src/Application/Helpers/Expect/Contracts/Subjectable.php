<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Expect\Contracts;

use Wordless\Application\Helpers\Expect\Contracts\Subjectable\DTO\ExpectSubjectDTO;
use Wordless\Infrastructure\Helper\Contracts\Subjectable as BaseSubjectable;

abstract class Subjectable extends BaseSubjectable
{
    /**
     * @param array $subject
     * @return ExpectSubjectDTO
     */
    public static function of(mixed $subject): ExpectSubjectDTO
    {
        return new ExpectSubjectDTO($subject);
    }
}
