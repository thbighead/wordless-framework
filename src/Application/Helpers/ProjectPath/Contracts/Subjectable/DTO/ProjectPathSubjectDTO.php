<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO;

use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetCurrentWorkingDirectory;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO\Traits\Internal;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;

abstract class ProjectPathSubjectDTO extends SubjectDTO
{
    use Internal;

    /**
     * @param string $from
     * @return string
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws PathNotFoundException
     */
    final public function relativeFrom(string $from): string
    {
        return $this->relative_from[$from]
            ?? $this->relative_from[$from] = ProjectPath::relativeTo($this->subject, $from);
    }

    /**
     * @param string $to
     * @return string
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws PathNotFoundException
     */
    final public function relativeTo(string $to): string
    {
        return $this->relative_to[$to]
            ?? $this->relative_to[$to] = ProjectPath::relativeTo($to, $this->subject);
    }
}
