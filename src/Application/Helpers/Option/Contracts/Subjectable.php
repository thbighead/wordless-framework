<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Option\Contracts;

use Wordless\Application\Helpers\Option\Contracts\Subjectable\DTO\OptionSubjectDTO;
use Wordless\Infrastructure\Helper\Contracts\Subjectable as BaseSubjectable;

abstract class Subjectable extends BaseSubjectable
{
    /**
     * @param string $subject
     * @return OptionSubjectDTO
     */
    public static function of(mixed $subject): OptionSubjectDTO
    {
        return new OptionSubjectDTO($subject);
    }
}
