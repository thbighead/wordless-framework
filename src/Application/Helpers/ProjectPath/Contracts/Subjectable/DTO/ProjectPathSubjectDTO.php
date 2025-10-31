<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO;

use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToDeletePath;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetCurrentWorkingDirectory;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO\Traits\Internal;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\Exceptions\PathTypeNotSupported;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;

abstract class ProjectPathSubjectDTO extends SubjectDTO
{
    use Internal;

    /**
     * @return void
     * @throws FailedToDeletePath
     * @throws PathNotFoundException
     */
    public function delete(): void
    {
        DirectoryFiles::delete($this->subject);
    }

    /**
     * @return self
     * @throws PathNotFoundException
     * @throws PathTypeNotSupported
     */
    public function previousPath(): self
    {
        return ProjectPath::of(dirname($this->subject));
    }

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
}
