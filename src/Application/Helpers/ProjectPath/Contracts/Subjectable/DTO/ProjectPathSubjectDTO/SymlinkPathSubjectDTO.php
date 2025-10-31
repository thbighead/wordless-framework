<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO;

use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToDeletePath;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\Exceptions\PathTypeNotSupported;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

final class SymlinkPathSubjectDTO extends ProjectPathSubjectDTO
{
    private string $target;

    /**
     * @return ProjectPathSubjectDTO
     * @throws PathNotFoundException
     * @throws PathTypeNotSupported
     */
    public function target(): ProjectPathSubjectDTO
    {
        return ProjectPath::of($this->target ?? $this->target = realpath($this->subject));
    }
}
