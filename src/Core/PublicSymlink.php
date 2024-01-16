<?php declare(strict_types=1);

namespace Wordless\Core;

use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetFileContent;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\PublicSymlink\Exceptions\InvalidPublicSymlinkTargetWithExceptions;
use Wordless\Core\PublicSymlink\Wlsymlink;

final class PublicSymlink
{
    public const EXCEPT_MARKER = '!';
    public const EXCEPT_SEPARATOR_MARKER = ',';

    private string $link_relative_path;
    private string $target_absolute_path;
    /** @var string[] $target_exceptions_relative_paths_from_public */
    private array $target_exceptions_relative_paths_from_public = [];
    /** @var string[] $target_exceptions_relative_paths_from_target */
    private array $target_exceptions_relative_paths_from_target = [];
    private string $target_relative_path;
    /** @var Wlsymlink[] $target_wlsymlinks */
    private array $target_wlsymlinks = [];

    /**
     * @param string $link_relative_path
     * @param string $target_relative_path
     * @param bool $should_search_for_wlsymlinks
     * @throws FailedToGetFileContent
     * @throws InvalidDirectory
     * @throws InvalidPublicSymlinkTargetWithExceptions
     * @throws PathNotFoundException
     */
    public function __construct(
        string $link_relative_path,
        string $target_relative_path,
        bool $should_search_for_wlsymlinks = true
    )
    {
        $this->link_relative_path = $link_relative_path;
        $this->setTargetPaths($target_relative_path);

        if ($should_search_for_wlsymlinks) {
            $this->recursiveSearchWlsymlinks();
        }
    }

    /**
     * @return string[]
     */
    public function exportExceptionsRelativePaths(): array
    {
        return [$this->target_relative_path, ...$this->target_exceptions_relative_paths_from_public];
    }

    /**
     * @return PublicSymlink[]
     * @throws InvalidDirectory
     */
    public function exportGeneratedSymlinksParsedFromExceptions(): array
    {
        if (!$this->hasExceptions()) {
            // avoids infinite .wlsymlinks recursion
            return $this->hasWlsymlinks() ? [] : [$this];
        }

        $included_relative_paths_from_target = DirectoryFiles::listFromDirectory(
            $this->target_absolute_path,
            $this->target_exceptions_relative_paths_from_target
        );

        foreach ($included_relative_paths_from_target as &$relative_path) {
            $relative_path = new self(
                "$this->link_relative_path/$relative_path",
                "$this->target_relative_path/$relative_path",
                false
            );
        }

        return $included_relative_paths_from_target;
    }

    /**
     * @return string
     * @throws PathNotFoundException
     */
    public function getLinkAbsolutePath(): string
    {
        return ProjectPath::public($this->link_relative_path);
    }

    public function getLinkRelativePath(): string
    {
        return $this->link_relative_path;
    }

    public function getTargetAbsolutePath(): string
    {
        return $this->target_absolute_path;
    }

    public function getTargetRelativePath(): string
    {
        return $this->target_relative_path;
    }

    /**
     * @return Wlsymlink[]
     */
    public function getWlsymlinks(): array
    {
        return $this->target_wlsymlinks;
    }

    public function hasExceptions(): bool
    {
        return !empty($this->target_exceptions_relative_paths_from_public);
    }

    public function hasWlsymlinks(): bool
    {
        return !empty($this->target_wlsymlinks);
    }

    public function isTargetingDirectory(): bool
    {
        return is_dir($this->target_absolute_path);
    }

    public function linkPathExists(): bool
    {
        try {
            $this->getLinkAbsolutePath();

            return true;
        } catch (PathNotFoundException) {
            return false;
        }
    }

    /**
     * @param string|string[] $exceptions
     * @param string|null $target_relative_path
     * @return void
     */
    private function addTargetExceptions(string|array $exceptions, ?string $target_relative_path = null): void
    {
        if (!is_array($exceptions)) {
            $exceptions = explode(self::EXCEPT_SEPARATOR_MARKER, $exceptions);
        }

        $target_relative_path = $target_relative_path ?? $this->target_relative_path;

        foreach ($exceptions as $exception) {
            $this->target_exceptions_relative_paths_from_target[] = $exception = trim($exception, '/');
            $this->target_exceptions_relative_paths_from_public[] = "$target_relative_path/$exception";
        }
    }

    private function parseTargetExceptions(string $target_relative_path_with_exceptions): string
    {
        [$target_relative_path, $exceptions] = explode(
            self::EXCEPT_MARKER,
            $target_relative_path_with_exceptions
        );

        $this->addTargetExceptions($exceptions, $target_relative_path = $this->trimPath($target_relative_path));

        return $target_relative_path;
    }

    private function rawTargetHasExceptions(string $target): bool
    {
        return Str::contains($target, self::EXCEPT_MARKER);
    }

    /**
     * @return void
     * @throws FailedToGetFileContent
     * @throws InvalidDirectory
     * @throws InvalidPublicSymlinkTargetWithExceptions
     * @throws PathNotFoundException
     */
    private function recursiveSearchWlsymlinks(): void
    {
        if (!$this->isTargetingDirectory()) {
            return;
        }

        foreach (DirectoryFiles::recursiveRead($this->target_absolute_path) as $absolute_filepath) {
            if (basename($absolute_filepath) !== Wlsymlink::FILENAME) {
                continue;
            }

            $this->target_wlsymlinks[] = new Wlsymlink($absolute_filepath, $this);
        }
    }

    /**
     * @param string $raw_target_relative_path
     * @return void
     * @throws InvalidPublicSymlinkTargetWithExceptions
     * @throws PathNotFoundException
     */
    private function setTargetPaths(string $raw_target_relative_path): void
    {
        $target_relative_path = trim($raw_target_relative_path, '/');

        if ($this->rawTargetHasExceptions($raw_target_relative_path)) {
            $target_relative_path = $this->parseTargetExceptions($raw_target_relative_path);
        }

        $this->target_absolute_path = ProjectPath::public($this->target_relative_path = $target_relative_path);

        if ($this->hasExceptions() && !$this->isTargetingDirectory()) {
            throw new InvalidPublicSymlinkTargetWithExceptions($raw_target_relative_path);
        }
    }

    /**
     * @param string $path
     * @return string
     */
    private function trimPath(string $path): string
    {
        return trim($path, '/');
    }
}
