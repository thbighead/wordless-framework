<?php

namespace App\Commands\MediaSync\Traits;

use App\Commands\MediaSync\Exceptions\FailedToCreateWordpressAttachment;
use App\Commands\MediaSync\Exceptions\FailedToCreateWordpressAttachmentMetadata;
use App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk;
use App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions\StopUploadsProcess;
use App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\InsertToDatabase;
use Generator;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\DirectoryFiles;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

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
     * @throws PathNotFoundException
     */
    private function getUploadsFilePathsCount(): int
    {
        $count = 0;

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
     * @throws PathNotFoundException
     */
    private function mountUploadsFilePathReaderGenerator(): ?Generator
    {
        return DirectoryFiles::recursiveRead($this->getUploadsDirectoryAbsolutePath());
    }

    /**
     * @return int
     * @throws Chunk\Exceptions\InvalidChunkValue
     * @throws FailedToCreateWordpressAttachment
     * @throws FailedToCreateWordpressAttachmentMetadata
     * @throws PathNotFoundException
     */
    private function processUploadsFiles(): int
    {
        $inserted_attachments_count = 0;
        $progressBar = $this->initializeProgressBar($this->getUploadsFilePathsCount());

        foreach ($this->mountUploadsFilePathReaderGenerator() as $uploaded_file_absolute_path) {
            try {
                $this->resolveFinishedChunk();
            } catch (StopUploadsProcess $exception) {
                break;
            }

            if (!$this->shouldProcessUploadedFilepath($uploaded_file_absolute_path)) {
                $progressBar->advance();
                continue;
            }

            $this->createAttachmentForUploadedFilepath($uploaded_file_absolute_path);

            $inserted_attachments_count++;
            $progressBar->advance();

            $this->resolveInterruption();
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
     * @throws Chunk\Exceptions\InvalidChunkValue
     * @throws FailedToCreateWordpressAttachment
     * @throws FailedToCreateWordpressAttachmentMetadata
     * @throws PathNotFoundException
     */
    private function syncFromUploadsDirectoryToDatabase()
    {
        require_once ProjectPath::wpCore('wp-admin/includes/image.php');
        require_once ProjectPath::wpCore('wp-admin/includes/media.php');

        $this->writelnInfo("\nChecking uploads directory...");

        $this->writelnSuccess("\nFinish (inserted: {$this->processUploadsFiles()})");
    }
}
