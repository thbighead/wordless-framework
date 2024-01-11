<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Helper\Contracts;

use Wordless\Infrastructure\Helper;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;

abstract class Subjectable extends Helper
{
    public static function of(mixed $subject): SubjectDTO
    {
        return new SubjectDTO($subject, static::class);
    }
}
