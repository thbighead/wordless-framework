<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Traits;

use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Traits\OptionsMounter\DTO\ChunkOptionDTO;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Traits\OptionsMounter\DTO\OnceOptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

trait OptionsMounter
{
    private function mountChunkOption(): ChunkOptionDTO
    {
        return new ChunkOptionDTO;
    }

    /**
     * @return OptionDTO[]
     */
    private function mountChunkOptions(): array
    {
        return [
            $this->mountChunkOption(),
            $this->mountOnceOption(),
        ];
    }

    private function mountOnceOption(): OnceOptionDTO
    {
        return new OnceOptionDTO;
    }
}
