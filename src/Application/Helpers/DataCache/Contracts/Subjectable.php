<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DataCache\Contracts;

use Wordless\Application\Helpers\DataCache\Contracts\Subjectable\DTO\DataCacheSubjectDTO;
use Wordless\Infrastructure\Helper\Contracts\Subjectable as BaseSubjectable;

abstract class Subjectable extends BaseSubjectable
{
    /**
     * @param string $subject
     * @return DataCacheSubjectDTO
     */
    public static function of(mixed $subject): DataCacheSubjectDTO
    {
        return new DataCacheSubjectDTO($subject);
    }
}
