<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Seeders\Contracts;

use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Commands\Exceptions\FailedToGetCommandOptionValue;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Commands\Traits\RunWpCliCommand;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Application\Helpers\Environment\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;

abstract class SeederCommand extends ConsoleCommand
{
    use LoadWpConfig;
    use RunWpCliCommand;

    protected const DEFAULT_NUMBER_OF_OBJECTS = 10;
    private const OPTION_QUANTITY = 'quantity';

    protected readonly Generator $faker;
    private readonly int $quantity;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        $this->faker = Factory::create();
    }

    /**
     * @return bool
     * @throws CannotResolveEnvironmentGet
     */
    public function canRun(): bool
    {
        return Environment::isNotRemote();
    }

    /**
     * @return ArgumentDTO[]
     */
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
                self::OPTION_QUANTITY,
                'Specify the quantity of objects to be created.',
                mode: OptionMode::required_value,
                default: static::DEFAULT_NUMBER_OF_OBJECTS
            ),
            ...$this->mountRunWpCliOptions(),
        ];
    }

    /**
     * @return int
     * @throws FailedToGetCommandOptionValue
     */
    protected function getQuantity(): int
    {
        try {
            return $this->quantity ??
                $this->quantity = max(abs((int)$this->input->getOption($option = self::OPTION_QUANTITY)), 1);
        } catch (InvalidArgumentException $exception) {
            throw new FailedToGetCommandOptionValue($option, $exception);
        }
    }
}
