<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Traits\OptionsMounter\DTO;

use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;

readonly class ChunkOptionDTO extends OptionDTO
{
    final public const OPTION_NAME = 'chunk';

    public function __construct()
    {
        parent::__construct(
            self::OPTION_NAME,
            'Asks to continue after the given number of uploads files are processed.',
            'c',
            OptionMode::optional_value,
            false
        );
    }
}
