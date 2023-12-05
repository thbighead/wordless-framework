<?php

namespace Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums;

use Symfony\Component\Console\Input\InputOption;

enum OptionMode: int
{
    case array_optional_values = InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL;
    case array_required_values = InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED;
    case optional_value = InputOption::VALUE_OPTIONAL;
    case no_value = InputOption::VALUE_NONE;
    case required_value = InputOption::VALUE_REQUIRED;
}
