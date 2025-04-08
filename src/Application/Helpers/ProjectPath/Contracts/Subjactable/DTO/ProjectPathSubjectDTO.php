<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\ProjectPath\Contracts\Subjactable\DTO;

use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetCurrentWorkingDirectory;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;

class ProjectPathSubjectDTO extends SubjectDTO
{
    /**
     * @param string $additional_path
     * @return $this
     * @throws PathNotFoundException
     */
    final public function additionalPath(string $additional_path): self
    {
        $this->subject = ProjectPath::realpath("$this->subject/$additional_path");

        return $this;
    }

    /**
     * @param string $from_absolute_path
     * @return $this
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws PathNotFoundException
     */
    final public function relativeFrom(string $from_absolute_path): self
    {
        $this->subject = ProjectPath::relativeTo($this->subject, $from_absolute_path);

        return $this;
    }

    /**
     * @param string $to_absolute_path
     * @return $this
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws PathNotFoundException
     */
    final public function relativeTo(string $to_absolute_path): self
    {
        $this->subject = ProjectPath::relativeTo($to_absolute_path, $this->subject);

        return $this;
    }
}
