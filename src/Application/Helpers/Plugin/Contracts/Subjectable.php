<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Plugin\Contracts;

use Wordless\Application\Helpers\Plugin\Contracts\Subjectable\DTO\PluginSubjectDTO;
use Wordless\Application\Helpers\Plugin\Contracts\Subjectable\DTO\PluginSubjectDTO\Exceptions\PluginNotFound;
use Wordless\Infrastructure\Helper\Contracts\Subjectable as BaseSubjectable;

abstract class Subjectable extends BaseSubjectable
{
    /**
     * @param string $subject
     * @return PluginSubjectDTO
     * @throws PluginNotFound
     */
    public static function of(mixed $subject): PluginSubjectDTO
    {
        return new PluginSubjectDTO($subject);
    }
}
