<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO;

use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateSymlink;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToDeletePath;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToPutFileContent;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToTravelDirectories;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\Exceptions\PathTypeNotSupported;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

final class DirectoryPathSubjectDTO extends ProjectPathSubjectDTO
{
    /**
     * @param string $additional_relative_path
     * @return ProjectPathSubjectDTO
     * @throws PathNotFoundException
     * @throws PathTypeNotSupported
     */
    public function addPath(string $additional_relative_path): ProjectPathSubjectDTO
    {
        return ProjectPath::of("$this->subject/$additional_relative_path");
    }

    /**
     * @param string $directory_name
     * @param bool $secure_mode
     * @param int|null $permissions
     * @return self
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws PathNotFoundException
     */
    public function createDirectory(string $directory_name, bool $secure_mode = true, ?int $permissions = null): self
    {
        DirectoryFiles::createDirectoryAt(
            $directory_path = "$this->subject/$directory_name",
            $secure_mode,
            $permissions
        );

        return new self($directory_path);
    }

    /**
     * @param string $filename
     * @param string $file_content
     * @param bool $secure_mode
     * @param int|null $permissions
     * @return FilePathSubjectDTO
     * @throws FailedToCreateDirectory
     * @throws FailedToPutFileContent
     * @throws PathNotFoundException
     */
    public function createFile(
        string $filename,
        string $file_content = '',
        bool   $secure_mode = true,
        ?int   $permissions = null
    ): FilePathSubjectDTO
    {
        DirectoryFiles::createFileAt(
            $filepath = "$this->subject/$filename",
            $file_content,
            $secure_mode,
            $permissions
        );

        return new FilePathSubjectDTO($filepath);
    }

    /**
     * @param string $symbolic_link_name
     * @param string $symbolic_link_target
     * @param string|null $from_absolute_path
     * @param bool $secure_mode
     * @return SymlinkPathSubjectDTO
     * @throws FailedToCreateSymlink
     * @throws FailedToTravelDirectories
     * @throws PathNotFoundException
     */
    public function createSymlink(
        string  $symbolic_link_name,
        string  $symbolic_link_target,
        ?string $from_absolute_path = null,
        bool   $secure_mode = true
    ): SymlinkPathSubjectDTO
    {
        DirectoryFiles::createSymbolicLink(
            $symlink_path = "$this->subject/$symbolic_link_name",
            $symbolic_link_target,
            $from_absolute_path,
            $secure_mode
        );

        return new SymlinkPathSubjectDTO($symlink_path);
    }

    /**
     * @param string $additional_relative_path
     * @param array $except
     * @param bool $delete_root
     * @return $this|self|null
     * @throws FailedToDeletePath
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    public function delete(string $additional_relative_path = '', array $except = [], bool $delete_root = true): ?self
    {
        DirectoryFiles::recursiveDelete($additional_relative_path, $except, $delete_root);

        // Checking if the subject path still exists after deletion
        try {
            $this->subject = ProjectPath::realpath($this->subject);
        } catch (PathNotFoundException) {
            return null;
        }

        return $this;
    }
}
