<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\ProjectPath\Contracts;

use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\Exceptions\PathTypeNotSupported;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Helper\Contracts\Subjectable as BaseSubjectable;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO\FilePathSubjectDTO;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO\DirectoryPathSubjectDTO;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO\SymlinkPathSubjectDTO;

abstract class Subjectable extends BaseSubjectable
{
    /**
     * @param string $full_path
     * @return string
     * @throws PathNotFoundException
     */
    abstract public static function realpath(string $full_path): string;

    /**
     * @param mixed $subject
     * @return ProjectPathSubjectDTO
     * @throws PathNotFoundException
     * @throws PathTypeNotSupported
     */
    public static function of(mixed $subject): ProjectPathSubjectDTO
    {
        $subject = static::realpath($subject);

        return match (true) {
            is_dir($subject) => new DirectoryPathSubjectDTO($subject),
            is_file($subject) => new FilePathSubjectDTO($subject),
            is_link($subject) => new SymlinkPathSubjectDTO($subject),
            default => throw new PathTypeNotSupported($subject),
        };
    }
}
