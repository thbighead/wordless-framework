<?php

namespace Wordless\Application\Commands\Seeders\Contracts;

use Faker\Factory;
use Faker\Generator;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;

abstract class BaseCreateDummyCommand extends ConsoleCommand
{
    use LoadWpConfig;

    final protected const OPTION_TOTAL = 'total';
    final protected const PROGRESS_MARK = '.';

    protected readonly Generator $faker;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        $this->faker = Factory::create();
    }

    protected function arguments(): array
    {
        return [];
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [
            OptionDTO::make(
                self::OPTION_TOTAL,
                'Specify the quantity of objects to be created.',
                mode: OptionMode::optional_value
            ),
        ];
    }
}
