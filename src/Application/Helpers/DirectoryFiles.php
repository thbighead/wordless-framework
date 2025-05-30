<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Exception;
use Generator;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToChangeDirectoryTo;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToChangePathPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCopyFile;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateSymlink;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToDeletePath;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetCurrentWorkingDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetFileContent;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToPutFileContent;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\NotAPhpFile;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Helper;

class DirectoryFiles extends Helper
{
    /**
     * @param string $path
     * @param int $permissions
     * @return void
     * @throws FailedToChangePathPermissions
     */
    public static function changePermissions(string $path, int $permissions): void
    {
        if (!chmod($path, $permissions)) {
            throw new FailedToChangePathPermissions($path, $permissions);
        }
    }

    /**
     * @param string $to
     * @return void
     * @throws FailedToChangeDirectoryTo
     */
    public static function changeWorkingDirectory(string $to): void
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
    public static function changeWorkingDirectoryDoAndGoBack(string $to, callable $do, ?string $back_to = null): mixed
    {
        $back_to = $back_to ?? static::getCurrentWorkingDirectory();

        static::changeWorkingDirectory($to);

        $result = $do();

        static::changeWorkingDirectory($back_to);

        return $result;
    }

    /**
     * @param string $from
     * @param string $to
     * @param bool $secure_mode
     * @return void
     * @throws FailedToCopyFile
     */
    public static function copyFile(string $from, string $to, bool $secure_mode = true): void
    {
        if ($secure_mode && file_exists($to)) {
            throw new FailedToCopyFile($from, $to, $secure_mode);
        }

        if (!is_dir($parent_target_directory_path = dirname($to))) {
            try {
                static::createDirectoryAt($parent_target_directory_path);
            } catch (Exception $exception) {
                throw new FailedToCopyFile($from, $to, $secure_mode, $exception);
            }
        }

        if (!copy($from, $to)) {
            throw new FailedToCopyFile($from, $to, $secure_mode);
        }
    }

    /**
     * @param string $from
     * @param string $date_subdirectory
     * @param bool $secure_mode
     * @return string
     * @throws FailedToCopyFile
     * @throws PathNotFoundException
     */
    public static function copyFileToWpUploads(
        string $from,
        string $date_subdirectory = '',
        bool   $secure_mode = true
    ): string
    {
        $wp_uploads_base_directory_target = empty($date_subdirectory)
            ? wp_upload_dir()['path']
            ?? throw new FailedToCopyFile($from, 'new uploads subdirectory', $secure_mode)
            : ProjectPath::wpUploads() . "/$date_subdirectory";
        $filename = basename($from);

        static::copyFile(
            $from,
            $destiny = "$wp_uploads_base_directory_target/$filename",
            $secure_mode
        );

        return $destiny;
    }

    /**
     * @param string $path
     * @param int|null $permissions
     * @return void
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws PathNotFoundException
     */
    public static function createDirectoryAt(string $path, ?int $permissions = null): void
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

        if (!mkdir($path, $permissions, true)) {
            throw new FailedToCreateDirectory($path);
        }
    }

    /**
     * @param string $filepath
     * @param string $file_content
     * @param bool $secure_mode
     * @param int|null $permissions
     * @return void
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws FailedToPutFileContent
     * @throws PathNotFoundException
     */
    public static function createFileAt(
        string $filepath,
        string $file_content = '',
        bool   $secure_mode = true,
        ?int   $permissions = null
    ): void
    {
        if (!is_dir($file_directory_path = dirname($filepath))) {
            static::createDirectoryAt($file_directory_path, $permissions);
        }

        if ($secure_mode && file_exists($filepath)) {
            return;
        }

        if (file_put_contents($filepath, $file_content) === false) {
            throw new FailedToPutFileContent($filepath, $file_content);
        }
    }

    /**
     * @param string $link_relative_path
     * @param string $target_relative_path
     * @param string|null $from_absolute_path
     * @return void
     * @throws FailedToChangeDirectoryTo
     * @throws FailedToCreateSymlink
     * @throws FailedToGetCurrentWorkingDirectory
     * @throws PathNotFoundException
     */
    public static function createSymbolicLink(
        string  $link_relative_path,
        string  $target_relative_path,
        ?string $from_absolute_path = null
    ): void
    {
        $from_absolute_path = $from_absolute_path ?? ProjectPath::root();

        static::changeWorkingDirectoryDoAndGoBack($from_absolute_path, function () use (
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
    public static function delete(string $path): void
    {
        $path = ProjectPath::path($path);

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
     * @param string $download_file_url
     * @param string $absolute_file_path
     * @return string
     * @throws FailedToPutFileContent
     */
    public static function downloadFileAt(string $download_file_url, string $absolute_file_path): string
    {
        if (is_dir($absolute_file_path)) {
            $absolute_file_path = Str::finishWith($absolute_file_path, DIRECTORY_SEPARATOR)
                . Str::afterLast($download_file_url, '/');
        }

        if (file_put_contents($absolute_file_path, fopen($download_file_url, 'r')) === false) {
            throw new FailedToPutFileContent(
                $absolute_file_path,
                "Content downloaded from $download_file_url."
            );
        }

        return $absolute_file_path;
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
     * @param string $php_file_path
     * @param array $variables_to_extract
     * @return string
     * @throws NotAPhpFile
     * @throws PathNotFoundException
     */
    public static function getFileEcho(string $php_file_path, array $variables_to_extract = []): string
    {
        if (!Str::endsWith($php_file_path = ProjectPath::realpath($php_file_path), '.php')) {
            throw new NotAPhpFile($php_file_path);
        }

        unset($variables_to_extract['php_file_path']);
        unset($variables_to_extract['variables_to_extract']);

        if (!empty($variables_to_extract)) {
            extract($variables_to_extract);
        }

        ob_start();
        include($php_file_path);
        $rendered_message = ob_get_contents();
        ob_end_clean();

        return $rendered_message;
    }

    /**
     * @param string $filepath
     * @return string
     * @throws FailedToGetFileContent
     * @throws PathNotFoundException
     */
    public static function getFileContent(string $filepath): string
    {
        if (($file_content = file_get_contents($filepath = ProjectPath::realpath($filepath))) === false) {
            throw new FailedToGetFileContent($filepath);
        }

        return $file_content;
    }

    /**
     * @param string $path
     * @return int
     * @throws FailedToGetDirectoryPermissions
     */
    public static function getPermissions(string $path): int
    {
        if (($permissions = fileperms($path)) === false) {
            throw new FailedToGetDirectoryPermissions($path);
        }

        return $permissions;
    }

    /**
     * @param string $directory
     * @param string[] $except
     * @param bool $absolute_paths
     * @return string[]
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    public static function listFromDirectory(string $directory, array $except = [], bool $absolute_paths = false): array
    {
        $raw_list = scandir($directory);

        if ($raw_list === false) {
            throw new InvalidDirectory($directory);
        }

        $list = array_diff($raw_list, ['.', '..'], $except);

        if ($absolute_paths) {
            foreach ($list as &$path) {
                $path = ProjectPath::realpath("$directory/$path");
            }
        }

        return $list;
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
    public static function recursiveCopy(string $from, string $to, array $except = [], bool $secure_mode = true): void
    {
        $from_real_path = ProjectPath::realpath($from);

        if ($except[$from_real_path] ?? in_array($from_real_path, $except)) {
            return;
        }

        if (is_link($from) || is_file($from)) {
            if (!file_exists($directory_destination = dirname($to))) {
                static::createDirectoryAt($directory_destination);
            }

            if ($secure_mode && file_exists($to)) {
                return;
            }

            static::copyFile($from, $to, $secure_mode);

            return;
        }

        if (!file_exists($to)) {
            static::createDirectoryAt($to);
        }

        foreach (static::listFromDirectory($from) as $file) {
            static::recursiveCopy("$from_real_path/$file", "$to/$file", $except, $secure_mode);
        }
    }

    /**
     * @param string $path
     * @param array $except
     * @param bool $delete_root
     * @return void
     * @throws FailedToDeletePath
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    public static function recursiveDelete(string $path, array $except = [], bool $delete_root = true): void
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

        foreach (static::listFromDirectory($real_path) as $file) {
            static::recursiveDelete("$real_path/$file", $except);
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
    public static function recursiveDeleteEmptyDirectories(string $path, bool $delete_root = true): void
    {
        if (!is_dir($real_path = ProjectPath::realpath($path))) {
            return;
        }

        if (empty($paths = static::listFromDirectory($real_path))) {
            static::delete($real_path);
            return;
        }

        foreach ($paths as $relative_path) {
            static::recursiveDeleteEmptyDirectories("$real_path/$relative_path");
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
     * @return Generator
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    public static function recursiveRead(string $path): Generator
    {
        if (is_dir($real_path = ProjectPath::realpath($path))) {
            foreach (static::listFromDirectory($real_path) as $file) {
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
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    public static function recursiveRemoveSymbolicLinks(string $path): void
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

        foreach (static::listFromDirectory($real_path) as $file) {
            static::recursiveRemoveSymbolicLinks("$real_path/$file");
        }
    }
}
