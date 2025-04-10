<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO;

use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToDeletePath;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetFileContent;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToPutFileContent;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\NotAPhpFile;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

final class FilePathSubjectDTO extends ProjectPathSubjectDTO
{
    private string $content;
    private string $php_echo;

    /**
     * @return string
     * @throws FailedToGetFileContent
     * @throws PathNotFoundException
     */
    public function getContent(): string
    {
        return $this->content ?? $this->content = DirectoryFiles::getFileContent($this->subject);
    }

    /**
     * @return string
     * @throws NotAPhpFile
     * @throws PathNotFoundException
     */
    public function getPhpEcho(): string
    {
        return $this->php_echo ?? $this->php_echo = DirectoryFiles::getFileEcho($this->subject);
    }

    /**
     * @return void
     * @throws FailedToDeletePath
     * @throws PathNotFoundException
     */
    public function delete(): void
    {
        DirectoryFiles::delete($this->subject);

        unset($this->content);
    }

    /**
     * @param string $content
     * @param bool $overwrite
     * @return $this
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws FailedToPutFileContent
     * @throws PathNotFoundException
     */
    public function writeContent(string $content, bool $overwrite = true): self
    {
        DirectoryFiles::createFileAt($this->subject, $content, !$overwrite);

        if ($overwrite) {
            $this->content = $content;
        }

        return $this;
    }
}
