<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Reflection\Contracts;

use Wordless\Application\Helpers\Reflection\Contracts\Subjectable\DTO\ReflectionSubjectDTO;
use Wordless\Infrastructure\Helper\Contracts\Subjectable as BaseSubjectable;

abstract class Subjectable extends BaseSubjectable
{
    public static function of(mixed $subject): ReflectionSubjectDTO
    {
        return new ReflectionSubjectDTO($subject);
    }
}
