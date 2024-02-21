<?php

namespace App\Commands;

use App\Commands\MediaSync\Exceptions\FailedToCreateWordpressAttachment;
use App\Commands\MediaSync\Exceptions\FailedToCreateWordpressAttachmentMetadata;
use App\Commands\MediaSync\Traits\SignalResolver;
use App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory;
use App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions\InvalidChunkValue;
use App\Commands\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions\InvalidOptionsUse;
use App\Commands\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase;
use App\Commands\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database\Exceptions\FailedToDeleteAttachment;
use App\Commands\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database\Exceptions\FailedToRetrieveAttachmentUrl;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\SignalableCommandInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Wordless\Adapters\ConsoleCommand;
use Wordless\Contracts\Command\LoadWpConfig;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Environment;

class MediaSync extends ConsoleCommand implements SignalableCommandInterface
{
    use LoadWpConfig;
    use SignalResolver;
    use SyncFromDatabaseToUploadsDirectory;
    use SyncFromUploadsDirectoryToDatabase;

    public const OPTION_NAME_CHUNK = 'chunk';
    public const OPTION_NAME_ONCE = 'once';
    private const INCLUDED_UPLOADS_CHILDREN_NAMES = [];
    private const OPTION_CHUNK_DEFAULT = 500;

    protected static $defaultName = 'media:sync';

    private array $already_synchronized_attachments = [];
    private string $uploads_base_url;

    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Synchronizes Wordpress media library.';
    }


    protected function help(): string
    {
        return 'Database entries not found into uploads directory are removed and files from uploads directory not found into database are inserted.';
    }

    protected function options(): array
    {
        return [...$this->mountChunkOptions()];
    }

    /**
     * @return int
     * @throws FailedToCreateWordpressAttachment
     * @throws FailedToCreateWordpressAttachmentMetadata
     * @throws FailedToDeleteAttachment
     * @throws FailedToRetrieveAttachmentUrl
     * @throws InvalidChunkValue
     * @throws InvalidOptionsUse
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $this->validateOptions();

        $this->syncFromDatabaseToUploadsDirectory();

        $this->resolveInterruption();

        $this->syncFromUploadsDirectoryToDatabase();

        return Command::SUCCESS;
    }

    private function getUploadsBaseUrl(): string
    {
        return $this->uploads_base_url ??
            $this->uploads_base_url = Environment::get('APP_URL') . '/wp-content/uploads/';
    }

    private function initializeProgressBar(int $max): ProgressBar
    {
        $progressBar = new ProgressBar($this->output, $max);

        $progressBar->start();

        return $progressBar;
    }
}

