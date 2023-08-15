<?php

namespace Wordless\Helpers;

use Generator;
use Wordless\Exceptions\FailedToChangeDirectoryTo;
use Wordless\Exceptions\FailedToCopyFile;
use Wordless\Exceptions\FailedToCreateDirectory;
use Wordless\Exceptions\FailedToCreateSymlink;
use Wordless\Exceptions\FailedToDeletePath;
use Wordless\Exceptions\FailedToGetCurrentWorkingDirectory;
use Wordless\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Exceptions\InvalidDirectory;
use Wordless\Exceptions\PathNotFoundException;

class DirectoryFiles
{
    /**
     * @param string $to
     * @return void
     * @throws FailedToChangeDirectoryTo
     */
    public static function changeWorkingDirectory(string $to)
    {
        if (!chdir($to)) {
            throw new FailedToChangeDirectoryTo($to);
        }
    }

    /**
     * @param string $to
     * @param callable $do
     * @param string|null $back_to
     * @return mixed
     * @throws FailedToChangeDirectoryTo
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws PathNotFoundException
     */
    public static function changeWorkingDirectoryDoAndGoBack(string $to, callable $do, ?string $back_to = null)
    {
        $back_to = $back_to ?? self::getCurrentWorkingDirectory();

        self::changeWorkingDirectory($to);

        $result = $do();

        self::changeWorkingDirectory($back_to);

        return $result;
    }

    /**
     * @param string $path
     * @param $permissions
     * @param bool $recursive
     * @return void
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws PathNotFoundException
     */
    public static function createDirectoryAt(string $path, $permissions = null, bool $recursive = true)
    {
        $ancestor_path = dirname($path);

        while (!is_dir($ancestor_path)) {
            $ancestor_path = dirname($ancestor_path);

            if (strlen($ancestor_path) < 2) {
                throw new PathNotFoundException($path);
            }
        }

        if (($permissions = $permissions ?? fileperms($ancestor_path)) === false) {
            throw new FailedToGetDirectoryPermissions($ancestor_path);
        }

        if (!mkdir($path, $permissions, $recursive)) {
            throw new FailedToCreateDirectory($path);
        }
    }

    /**
     * @param string $link_relative_path
     * @param string $target_relative_path
     * @param string|null $from_absolute_path
     * @return void
     * @throws FailedToCreateSymlink
     * @throws FailedToChangeDirectoryTo
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws PathNotFoundException
     */
    public static function createSymlink(
        string  $link_relative_path,
        string  $target_relative_path,
        ?string $from_absolute_path = null
    )
    {
        $from_absolute_path = $from_absolute_path ?? ProjectPath::root();

        self::changeWorkingDirectoryDoAndGoBack($from_absolute_path, function () use (
            $link_relative_path,
            $target_relative_path,
            $from_absolute_path
        ) {
            $target_relative_path_from_link_parent_directory = str_repeat(
                    '../',
                    Str::countSubstring($link_relative_path, '/')
                ) . $target_relative_path;

            if (!symlink($target_relative_path_from_link_parent_directory, $link_relative_path)) {
                throw new FailedToCreateSymlink($link_relative_path, $target_relative_path, $from_absolute_path);
            }
        });
    }

    /**
     * @param string $path
     * @throws FailedToDeletePath
     * @throws PathNotFoundException
     */
    public static function delete(string $path)
    {
        if (($path = realpath($path)) === false) {
            throw new PathNotFoundException($path);
        }

        if (is_dir($path)) {
            if (!rmdir($path)) {
                throw new FailedToDeletePath($path);
            }

            return;
        }

        if (!unlink($path)) {
            throw new FailedToDeletePath($path);
        }
    }

    /**
     * @return string
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws PathNotFoundException
     */
    public static function getCurrentWorkingDirectory(): string
    {
        $current_working_directory = getcwd();

        if ($current_working_directory === false) {
            throw new FailedToGetCurrentWorkingDirectory;
        }

        return ProjectPath::realpath($current_working_directory);
    }

    /**
     * @param string $directory
     * @param array $except
     * @return array
     * @throws InvalidDirectory
     */
    public static function listFromDirectory(string $directory, array $except = []): array
    {
        $raw_list = scandir($directory);

        if ($raw_list === false) {
            throw new InvalidDirectory($directory);
        }

        return array_diff($raw_list, ['.', '..'], $except);
    }

    /**
     * @param string $from
     * @param string $to
     * @param string[] $except
     * @param bool $secure_mode If true, only copy if file does not exist in destination.
     * @return void
     * @throws FailedToCopyFile
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws PathNotFoundException
     * @throws InvalidDirectory
     */
    public static function recursiveCopy(string $from, string $to, array $except = [], bool $secure_mode = true)
    {
        $from_real_path = ProjectPath::realpath($from);

        if ($except[$from_real_path] ?? in_array($from_real_path, $except)) {
            return;
        }

        if (is_link($from) || is_file($from)) {
            if (!file_exists($directory_destination = dirname($to))) {
                self::createDirectoryAt($directory_destination);
            }

            if ($secure_mode && file_exists($to)) {
                return;
            }

            if (!copy($from, $to)) {
                throw new FailedToCopyFile($from, $to, $secure_mode);
            }

            return;
        }

        if (!file_exists($to)) {
            self::createDirectoryAt($to);
        }

        $files = self::listFromDirectory($from);

        foreach ($files as $file) {
            self::recursiveCopy("$from_real_path/$file", "$to/$file", $except, $secure_mode);
        }
    }

    /**
     * @param string $path
     * @param string[] $except
     * @param bool $delete_root
     * @throws FailedToDeletePath
     * @throws PathNotFoundException
     */
    public static function recursiveDelete(string $path, array $except = [], bool $delete_root = true)
    {
        $real_path = ProjectPath::realpath($path);

        if ($except[$real_path] ?? in_array($real_path, $except)) {
            return;
        }

        if (is_link($real_path) || is_file($real_path)) {
            if (!unlink($real_path)) {
                throw new FailedToDeletePath($real_path);
            }

            return;
        }

        $files = array_diff(scandir($real_path), ['.', '..']);

        foreach ($files as $file) {
            self::recursiveDelete("$real_path/$file", $except);
        }

        if (!$delete_root) {
            return;
        }

        if (!rmdir($real_path)) {
            throw new FailedToDeletePath($real_path);
        }
    }

    /**
     * @param string $path
     * @param bool $delete_root
     * @return void
     * @throws FailedToDeletePath
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    public static function recursiveDeleteEmptyDirectories(string $path, bool $delete_root = true)
    {
        if (!is_dir($real_path = ProjectPath::realpath($path))) {
            return;
        }

        if (empty($paths = self::listFromDirectory($real_path))) {
            self::delete($real_path);
            return;
        }

        foreach ($paths as $relative_path) {
            self::recursiveDeleteEmptyDirectories("$real_path/$relative_path");
        }

        if (!$delete_root) {
            return;
        }

        if (!rmdir($real_path)) {
            throw new FailedToDeletePath($real_path);
        }
    }

    /**
     * @param string $path
     * @return Generator|void
     * @throws PathNotFoundException
     */
    public static function recursiveRead(string $path)
    {
        $real_path = ProjectPath::realpath($path);

        if (is_dir($real_path)) {
            $files = array_diff(scandir($real_path), ['.', '..']);

            foreach ($files as $file) {
                yield from static::recursiveRead("$real_path/$file");
            }

            return;
        }

        yield $real_path;
    }

    /**
     * @param string $path
     * @return void
     * @throws FailedToDeletePath
     * @throws PathNotFoundException
     */
    public static function recursiveRemoveSymbolicLinks(string $path)
    {
        $real_path = ProjectPath::realpath($path);

        if (is_link($path)) {
            if (!unlink($path)) {
                throw new FailedToDeletePath($path);
            }

            return;
        }

        if (is_file($real_path)) {
            return;
        }

        $files = array_diff(scandir($real_path), ['.', '..']);

        foreach ($files as $file) {
            self::recursiveRemoveSymbolicLinks("$real_path/$file");
        }
    }
}
