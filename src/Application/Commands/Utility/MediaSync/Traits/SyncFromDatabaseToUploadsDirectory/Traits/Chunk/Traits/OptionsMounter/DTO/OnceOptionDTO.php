<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\MediaSync\Traits\SyncFromDatabaseToUploadsDirectory\Traits\Chunk\Traits\OptionsMounter\DTO;

use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;

readonly class OnceOptionDTO extends OptionDTO
{
    final public const OPTION_NAME = 'once';

    public function __construct()
    {
        parent::__construct(
            self::OPTION_NAME,
            'Avoids continue question by processing only one chunk.',
            'o',
            OptionMode::no_value
        );
    }
}
