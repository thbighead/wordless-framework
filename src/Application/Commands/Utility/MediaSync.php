<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\SignalableCommandInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Dotenv\Exception\PathException;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Commands\Utility\MediaSync\Exceptions\FailedToCreateWordpressAttachment;
use Wordless\Application\Commands\Utility\MediaSync\Exceptions\FailedToCreateWordpressAttachmentMetadata;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions\InvalidChunkValue;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions\InvalidOptionsUsage;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database\Exceptions\FailedToDeleteAttachment;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromUploadsDirectoryToDatabase\Traits\Database\Exceptions\FailedToRetrieveAttachmentUrl;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\Traits\SignalResolver;

class MediaSync extends ConsoleCommand implements SignalableCommandInterface
{
    use LoadWpConfig;
    use SignalResolver;
    use SyncFromDatabaseToUploadsDirectory;
    use SyncFromUploadsDirectoryToDatabase;

    final public const COMMAND_NAME = 'media:sync';
    private const INCLUDED_UPLOADS_CHILDREN_NAMES = [];
    private const OPTION_CHUNK_DEFAULT = 500;

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
     * @throws DotEnvNotSetException
     * @throws FailedToCreateWordpressAttachment
     * @throws FailedToCreateWordpressAttachmentMetadata
     * @throws FailedToDeleteAttachment
     * @throws FailedToRetrieveAttachmentUrl
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws InvalidChunkValue
     * @throws InvalidDirectory
     * @throws InvalidOptionsUsage
     * @throws LogicException
     * @throws PathException
     * @throws PathNotFoundException
     * @throws RuntimeException
     */
    protected function runIt(): int
    {
        $this->validateOptions();

        $this->syncFromDatabaseToUploadsDirectory();

        $this->resolveCommandIfInterrupted();

        $this->syncFromUploadsDirectoryToDatabase();

        return Command::SUCCESS;
    }

    /**
     * @return string
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
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
