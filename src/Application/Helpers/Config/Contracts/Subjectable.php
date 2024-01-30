<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Config\Contracts;

use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO;
use Wordless\Infrastructure\Helper\Contracts\Subjectable as BaseSubjectable;

abstract class Subjectable extends BaseSubjectable
{
    /**
     * @param string $subject
     * @return ConfigSubjectDTO
     */
    public static function of(mixed $subject): ConfigSubjectDTO
    {
        return new ConfigSubjectDTO($subject);
    }
}
