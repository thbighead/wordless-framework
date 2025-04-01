<?php declare(strict_types=1);

namespace Wordless\Core\PublicSymlink;

use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\PublicSymlink;

final class Resolver
{
    private array $symlinks = [];
    private array $target_exceptions = [];

    /**
     * @param PublicSymlink $symlink
     * @return void
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    public function addSymlink(PublicSymlink $symlink): void
    {
        switch (true) {
            case !$symlink->isTargetingDirectory():
            case !$symlink->hasExceptions() && !$symlink->hasWlsymlinks():
                $this->symlinks[$symlink->getLinkRelativePath()] = $symlink->getTargetRelativePath();
                break;
            default:
                $this->extractSymlinksAndExceptionsFrom($symlink);
                break;
        }
    }

    /**
     * @return array<string, string>
     */
    public function retrieveCleanedSymlinks(): array
    {
        return $this->retrieveOrderedSymlinksByLinkPathDepthDescending(
            $this->retrieveFilteredSymlinks($this->symlinks)
        );
    }

    /**
     * @param PublicSymlink $symlink
     * @return void
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private function extractSymlinksAndExceptionsFrom(PublicSymlink $symlink): void
    {
        $this->registerTargetException($symlink->getTargetRelativePath());

        foreach ($symlink->exportExceptionsRelativePaths() as $target_exception_relative_path) {
            $this->registerTargetException($target_exception_relative_path);
        }

        foreach ($symlink->exportGeneratedSymlinksParsedFromExceptions() as $generatedSymlink) {
            $this->addSymlink($generatedSymlink);
        }

        foreach ($symlink->getWlsymlinks() as $wlsymlink) {
            foreach ($wlsymlink->getGeneratedSymlinks() as $generatedSymlink) {
                $this->addSymlink($generatedSymlink);
            }
        }
    }

    /**
     * @param array<string, string> $symlinks
     * @return array<string, string>
     */
    private function retrieveFilteredSymlinks(array $symlinks): array
    {
        $filtered_symlinks = [];
        $already_listed = [];

        foreach ($symlinks as $link_relative_path => $target_relative_path) {
            if ($this->isTargetAnException($target_relative_path)) {
                continue;
            }

            $listing = "$link_relative_path=>$target_relative_path";

            if ($already_listed[$listing] ?? false) {
                continue;
            }

            $already_listed[$listing] = true;
            $filtered_symlinks[$link_relative_path] = $target_relative_path;
        }

        return $filtered_symlinks;
    }

    /**
     * @param array<string, string> $symlinks
     * @return array<string, string>
     */
    private function retrieveOrderedSymlinksByLinkPathDepthDescending(array $symlinks): array
    {
        $paths_list = array_keys($symlinks);

        usort($paths_list, function (string $link_relative_path, string $next_link_relative_path): int {
            $link_relative_path_depth = Str::countSubstring($link_relative_path, '/');
            $next_link_relative_path_depth = Str::countSubstring($next_link_relative_path, '/');

            return $next_link_relative_path_depth - $link_relative_path_depth;
        });

        $symlinks = [];

        foreach ($paths_list as $path) {
            $symlinks[$path] = $this->symlinks[$path];
        }

        return $symlinks;
    }

    private function isTargetAnException(string $target): bool
    {
        return (bool)($this->target_exceptions[$target] ?? false);
    }

    private function registerTargetException(string $target_exception_relative_path_from_public): void
    {
        $this->target_exceptions[$target_exception_relative_path_from_public] =
            $target_exception_relative_path_from_public;
    }
}
