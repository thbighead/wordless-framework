<?php

namespace Wordless\Application\Commands\Seeder;


use Exception;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Commands\WpCliCaller;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;

class Seeder extends ConsoleCommand
{
    use LoadWpConfig;

    protected const PROGRESS_MARK = '.';
    protected const OPTION_TOTAL = 'total';

    protected static Generator $faker;

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        if (!isset(self::$faker)) {
            self::$faker = Factory::create();
        }

    }

    protected function arguments(): array
    {
        return [];
    }

    protected function help(): string
    {
        return 'A custom command. More info at https://symfony.com/doc/current/console.html';
    }

    protected function options(): array
    {
        return [
            new OptionDTO(
                self::OPTION_TOTAL,
                'Specify the quantity of posts to be created.',
                mode: OptionMode::optional_value
            ),
        ];
    }

    protected function description(): string
    {
        // TODO: Implement description() method.
    }

    protected function runIt(): int
    {
        // TODO: Implement runIt() method.
    }
}
