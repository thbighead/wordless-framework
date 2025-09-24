<?php declare(strict_types=1);

namespace Wordless\Core\PublicSymlink;

use Generator;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\CannotReadPath;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetFileContent;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\PublicSymlink;
use Wordless\Core\PublicSymlink\Exceptions\InvalidPublicSymlinkTargetWithExceptions;
use Wordless\Core\PublicSymlink\Exceptions\PublicSymlinkParseFailed;
use Wordless\Core\PublicSymlink\Wlsymlink\Exceptions\WlsymlinkParseFailed;

final class Wlsymlink
{
    public const FILENAME = '.wlsymlinks';

    private string $absolute_filepath;
    private string $absolute_parent_directory;
    private array $file_content_lines = [];
    private PublicSymlink $foundFromSymlink;
    /** @var PublicSymlink[] $generatedSymlinks */
    private array $generatedSymlinks = [];
    /** @var string[] $mapped_relative_paths_exceptions_from_public */
    private array $mapped_relative_paths_exceptions_from_public = [];
    /** @var string[] $mapped_relative_paths_exceptions_from_parent_directory */
    private array $mapped_relative_paths_exceptions_from_parent_directory = [];
    /** @var string[] $mapped_relative_paths_from_parent_directory */
    private array $mapped_relative_paths_from_parent_directory = [];
    /** @var string[] $mapped_relative_paths_from_public */
    private array $mapped_relative_paths_from_public = [];
    private string $relative_parent_directory;

    /**
     * @param string $absolute_filepath
     * @param PublicSymlink $foundFromSymlink
     * @throws WlsymlinkParseFailed
     */
    public function __construct(string $absolute_filepath, PublicSymlink $foundFromSymlink)
    {
        $this->foundFromSymlink = $foundFromSymlink;
        try {
            $this->setPaths($absolute_filepath)
                ->loadFileContentLines()
                ->extractRelativePathsFromFile()
                ->extractRelativePathsExceptionsFromFile()
                ->setGeneratedSymlinks();
        } catch (FailedToGetFileContent
        |InvalidDirectory
        |PathNotFoundException
        |PublicSymlinkParseFailed $exception) {
            throw new WlsymlinkParseFailed($exception);
        }
    }

    public function getAbsoluteFilepath(): string
    {
        return $this->absolute_filepath;
    }

    public function getFoundFromSymlink(): PublicSymlink
    {
        return $this->foundFromSymlink;
    }

    /**
     * @return string[]
     */
    public function getMappedRelativePathsExceptionsFromPublic(): array
    {
        return $this->mapped_relative_paths_exceptions_from_public;
    }

    /**
     * @return string[]
     */
    public function getMappedRelativePathsFromParentDirectory(): array
    {
        return $this->mapped_relative_paths_from_parent_directory;
    }

    /**
     * @return string[]
     */
    public function getMappedRelativePathsFromPublic(): array
    {
        return $this->mapped_relative_paths_from_public;
    }

    /**
     * @return PublicSymlink[]
     */
    public function getGeneratedSymlinks(): array
    {
        return $this->generatedSymlinks;
    }

    /**
     * @return Wlsymlink
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private function extractRelativePathsExceptionsFromFile(): Wlsymlink
    {
        $trimmed_paths_from_file_content = [];

        foreach ($this->file_content_lines as $line) {
            $trimmed_paths_from_file_content[] = trim($line, '/');
        }

        $exceptions_from_file = DirectoryFiles::listFromDirectory(
            $this->absolute_parent_directory,
            $trimmed_paths_from_file_content
        );

        foreach ($exceptions_from_file as $relative_path) {
            $this->mapped_relative_paths_exceptions_from_parent_directory[] = $relative_path;
            $this->mapped_relative_paths_exceptions_from_public[] = "$this->relative_parent_directory/$relative_path";
        }

        return $this;
    }

    private function extractRelativePathsFromFile(): Wlsymlink
    {
        foreach ($this->file_content_lines as $relative_path) {
            $relative_path = trim($relative_path, '/');
            $this->mapped_relative_paths_from_parent_directory[] = $relative_path;
            $this->mapped_relative_paths_from_public[] = "$this->relative_parent_directory/$relative_path";
        }

        return $this;
    }

    private function guessRelativeFilepathParentDirectory(): string
    {
        $relative_filepath = trim(Str::after(
            $this->absolute_parent_directory,
            $this->foundFromSymlink->getTargetAbsolutePath()
        ), '/');

        if (empty($relative_filepath)) {
            return $this->foundFromSymlink->getTargetRelativePath();
        }

        return "{$this->foundFromSymlink->getTargetRelativePath()}/$relative_filepath";
    }

    private function areSubOrSamePaths(string $parent_directory_path, string $child_directory_path): bool
    {
        return Str::beginsWith($child_directory_path, $parent_directory_path);
    }

    /**
     * @return Wlsymlink
     * @throws FailedToGetFileContent
     */
    private function loadFileContentLines(): Wlsymlink
    {
        if (($wlsymlinks_content = file_get_contents($this->absolute_filepath)) === false) {
            throw new FailedToGetFileContent($this->absolute_filepath);
        }

        $relative_paths = array_filter(explode(PHP_EOL, $wlsymlinks_content));

        $this->file_content_lines = $relative_paths;

        return $this;
    }

    /**
     * @return PublicSymlink
     * @throws PublicSymlinkParseFailed
     */
    private function mountPublicSymlinkFromFileContent(): PublicSymlink
    {
        $exceptions = implode(
            PublicSymlink::EXCEPT_SEPARATOR_MARKER,
            $this->mapped_relative_paths_exceptions_from_parent_directory
        );

        $target_relative_path = $this->relative_parent_directory . PublicSymlink::EXCEPT_MARKER . $exceptions;
        $link_relative_path = $this->foundFromSymlink->getLinkRelativePath() . Str::after(
                $this->relative_parent_directory,
                $this->foundFromSymlink->getTargetRelativePath()
            );

        return new PublicSymlink($link_relative_path, $target_relative_path, false);
    }

    /**
     * @param PublicSymlink $from
     * @return Generator<PublicSymlink>
     * @throws PublicSymlinkParseFailed
     */
    private function mountSymlinksUntilOrigin(PublicSymlink $from): Generator
    {
        $origin_relative_directory_path_from_public = $this->foundFromSymlink->getTargetRelativePath();
        $exception = basename($from->getTargetRelativePath());
        $relative_directory_path_from_public = dirname($from->getTargetRelativePath());

        while ($this->areSubOrSamePaths(
            $origin_relative_directory_path_from_public,
            $relative_directory_path_from_public
        )) {
            $target_relative_path = $relative_directory_path_from_public . PublicSymlink::EXCEPT_MARKER . $exception;
            $link_relative_path = $this->foundFromSymlink->getLinkRelativePath() . Str::after(
                    $relative_directory_path_from_public,
                    $origin_relative_directory_path_from_public
                );

            yield new PublicSymlink($link_relative_path, $target_relative_path, false);

            $exception = basename($relative_directory_path_from_public);
            $relative_directory_path_from_public = dirname($relative_directory_path_from_public);
        }
    }

    /**
     * @return void
     * @throws PublicSymlinkParseFailed
     */
    private function setGeneratedSymlinks(): void
    {
        $this->generatedSymlinks[] = $fileSymlink = $this->mountPublicSymlinkFromFileContent();

        foreach ($this->mountSymlinksUntilOrigin($fileSymlink) as $symlink) {
            $this->generatedSymlinks[] = $symlink;
        }
    }

    private function setPaths(string $absolute_filepath): Wlsymlink
    {
        $this->absolute_filepath = $absolute_filepath;
        $this->absolute_parent_directory = dirname($this->absolute_filepath);
        $this->relative_parent_directory = $this->guessRelativeFilepathParentDirectory();

        return $this;
    }
}
