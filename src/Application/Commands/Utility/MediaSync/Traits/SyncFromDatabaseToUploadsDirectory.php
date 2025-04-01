<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits;

use Generator;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Dotenv\Exception\PathException;
use Wordless\Application\Commands\Utility\MediaSync\Exceptions\FailedToCreateWordpressAttachment;
use Wordless\Application\Commands\Utility\MediaSync\Exceptions\FailedToCreateWordpressAttachmentMetadata;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions\InvalidChunkValue;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions\StopUploadsProcess;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\InsertToDatabase;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Exceptions\DotEnvNotSetException;

trait SyncFromDatabaseToUploadsDirectory
{
    use Chunk;
    use InsertToDatabase;

    private string $uploads_directory_absolute_path;

    private function extractFilenameFromAbsolutePath(string $absolute_file_path): string
    {
        return Str::afterLast($absolute_file_path, '/');
    }

    /**
     * @param string $absolute_file_path
     * @return string
     * @throws PathNotFoundException
     */
    private function extractUploadsChildDirectory(string $absolute_file_path): string
    {
        $relative_file_path_from_uploads = ltrim(
            Str::after($absolute_file_path, $this->getUploadsDirectoryAbsolutePath()),
            '/'
        );

        return Str::before($relative_file_path_from_uploads, '/');
    }

    /**
     * @return string
     * @throws PathNotFoundException
     */
    private function getUploadsDirectoryAbsolutePath(): string
    {
        return $this->uploads_directory_absolute_path ??
            $this->uploads_directory_absolute_path = ProjectPath::wpUploads();
    }

    /**
     * @return int
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private function getUploadsFilePathsCount(): int
    {
        $count = 0;

        /** @noinspection PhpUnusedLocalVariableInspection */
        foreach ($this->mountUploadsFilePathReaderGenerator() as $uploaded_file_absolute_path) {
            $count++;
        }

        return $count;
    }

    /**
     * @param string $absolute_file_path
     * @return bool
     * @throws PathNotFoundException
     */
    private function isAbsoluteFilePathValid(string $absolute_file_path): bool
    {
        if (!$this->isOriginalFile($absolute_file_path)) {
            return false;
        }

        $uploads_child_directory = $this->extractUploadsChildDirectory($absolute_file_path);

        return $this->isIntoYearDirectory($uploads_child_directory)
            || $this->isIntoIncludedDirectory($uploads_child_directory);
    }

    private function isAttachmentAlreadySynchronized(string $uploaded_file_absolute_path): bool
    {
        return !is_null($this->already_synchronized_attachments[$uploaded_file_absolute_path] ?? null);
    }

    private function isIntoIncludedDirectory(string $uploads_child_directory): bool
    {
        return isset(self::INCLUDED_UPLOADS_CHILDREN_NAMES[$uploads_child_directory])
            || in_array($uploads_child_directory, self::INCLUDED_UPLOADS_CHILDREN_NAMES);
    }

    private function isIntoYearDirectory(string $directory_name): bool
    {
        return (bool)preg_match('/\d{4}/', $directory_name);
    }

    private function isOriginalFile(string $absolute_file_path): bool
    {
        return !preg_match(
            '/.+-\d{2,4}x\d{2,4}\.(jpg|jpeg|gif|png|webp|bmp)$/i',
            $this->extractFilenameFromAbsolutePath($absolute_file_path)
        );
    }

    /**
     * @return Generator|null
     * @throws InvalidDirectory
     * @throws PathNotFoundException
     */
    private function mountUploadsFilePathReaderGenerator(): ?Generator
    {
        return DirectoryFiles::recursiveRead($this->getUploadsDirectoryAbsolutePath());
    }

    /**
     * @return int
     * @throws DotEnvNotSetException
     * @throws FailedToCreateWordpressAttachment
     * @throws FailedToCreateWordpressAttachmentMetadata
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws InvalidChunkValue
     * @throws InvalidDirectory
     * @throws LogicException
     * @throws PathNotFoundException
     * @throws RuntimeException
     */
    private function processUploadsFiles(): int
    {
        $inserted_attachments_count = 0;
        $progressBar = $this->initializeProgressBar($this->getUploadsFilePathsCount());

        foreach ($this->mountUploadsFilePathReaderGenerator() as $uploaded_file_absolute_path) {
            try {
                $this->resolveFinishedChunk();
            } catch (StopUploadsProcess) {
                break;
            }

            if (!$this->shouldProcessUploadedFilepath($uploaded_file_absolute_path)) {
                $progressBar->advance();
                continue;
            }

            $this->createAttachmentForUploadedFilepath($uploaded_file_absolute_path);

            $inserted_attachments_count++;
            $progressBar->advance();

            $this->resolveCommandIfInterrupted();
        }

        $progressBar->finish();

        return $inserted_attachments_count;
    }

    /**
     * @param string $uploaded_file_absolute_path
     * @return bool
     * @throws PathNotFoundException
     */
    private function shouldProcessUploadedFilepath(string $uploaded_file_absolute_path): bool
    {
        return $this->isAbsoluteFilePathValid($uploaded_file_absolute_path)
            && !$this->isAttachmentAlreadySynchronized($uploaded_file_absolute_path);
    }

    /**
     * @return void
     * @throws DotEnvNotSetException
     * @throws FailedToCreateWordpressAttachment
     * @throws FailedToCreateWordpressAttachmentMetadata
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws InvalidChunkValue
     * @throws InvalidDirectory
     * @throws LogicException
     * @throws PathException
     * @throws PathNotFoundException
     * @throws RuntimeException
     */
    private function syncFromUploadsDirectoryToDatabase(): void
    {
        require_once ProjectPath::wpCore('wp-admin/includes/image.php');
        require_once ProjectPath::wpCore('wp-admin/includes/media.php');

        $this->writelnInfo("\nChecking uploads directory...");

        $this->writelnSuccess("\nFinish (inserted: {$this->processUploadsFiles()})");
    }
}
