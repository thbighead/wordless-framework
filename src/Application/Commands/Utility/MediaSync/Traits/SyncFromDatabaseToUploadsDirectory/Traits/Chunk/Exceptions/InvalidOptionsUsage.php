<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Exceptions;

use LogicException;
use Throwable;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Traits\OptionsMounter\DTO\ChunkOptionDTO;
use Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Traits\OptionsMounter\DTO\OnceOptionDTO;
use Wordless\Infrastructure\Enums\ExceptionCode;

class InvalidOptionsUsage extends LogicException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Used --'
            . OnceOptionDTO::OPTION_NAME
            . ' option without --'
            . ChunkOptionDTO::OPTION_NAME
            . ' option.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
