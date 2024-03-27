<?php declare(strict_types=1);

namespace Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums;

use Symfony\Component\Console\Input\InputArgument;

enum ArgumentMode: int
{
    case array = InputArgument::IS_ARRAY;
    case array_optional = InputArgument::IS_ARRAY | InputArgument::OPTIONAL;
    case array_required = InputArgument::IS_ARRAY | InputArgument::REQUIRED;
    case optional = InputArgument::OPTIONAL;
    case required = InputArgument::REQUIRED;
}
