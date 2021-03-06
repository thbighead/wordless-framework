<?php

namespace Wordless\Helpers;

use Generator;
use Wordless\Exceptions\FailedToCopyFile;
use Wordless\Exceptions\FailedToCreateDirectory;
use Wordless\Exceptions\FailedToDeletePath;
use Wordless\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Exceptions\InvalidDirectory;
use Wordless\Exceptions\PathNotFoundException;

class DirectoryFiles
{
    /**
     * @param string $path
     * @param $permissions
     * @param bool $recursive
     * @return void
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     */
    public static function createDirectoryAt(string $path, $permissions = null, bool $recursive = true)
    {
        $parent_path = dirname($path);

        if (($permissions = $permissions ?? fileperms($parent_path)) === false) {
            throw new FailedToGetDirectoryPermissions($parent_path);
        }

        if (!mkdir($path, $permissions, $recursive)) {
            throw new FailedToCreateDirectory($path);
        }
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