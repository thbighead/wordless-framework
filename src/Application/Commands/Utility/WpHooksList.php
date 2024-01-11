<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException as SymfonyInvalidArgumentException;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Commands\Utility\WpHooksList\Exceptions\InvalidHookType;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;
use Wordless\Wordpress\Hook;
use Wordless\Wordpress\Hook\Enums\Action;
use Wordless\Wordpress\Hook\Enums\Filter;
use Wordless\Wordpress\Hook\Enums\Type;

class WpHooksList extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'wp:hooks';
    private const DIFF_MODE = 'diff';
    private const HOOK_TYPE_ARGUMENT_NAME = 'hook_type';

    private string $given_hook_type;

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [
            ArgumentDTO::make(
                self::HOOK_TYPE_ARGUMENT_NAME,
                'The type of hooks to list. Must be one of: ' . Type::casesListAsString(),
                ArgumentMode::required
            ),
        ];
    }

    protected function description(): string
    {
        return 'List all hooks in your Wordpress installation.';
    }


    protected function help(): string
    {
        return 'List all hooks of the given type in your Wordpress installation.';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [
            OptionDTO::make(
                self::DIFF_MODE,
                'Shows the difference between Framework Enums and Wordpress installation.',
                'd',
                OptionMode::no_value
            ),
        ];
    }

    /**
     * @return int
     * @throws SymfonyInvalidArgumentException
     * @throws InvalidHookType
     * @throws InvalidArgumentException
     */
    protected function runIt(): int
    {
        if ($this->isDiffMode()) {
            $this->resolveDiffMode();

            return Command::SUCCESS;
        }

        $this->writeTable(
            [
                Str::titleCase($this->getHookTypeArgument()) . ' hook names',
                'How many times fired',
            ],
            $this->prepareTableContent()
        );

        return Command::SUCCESS;
    }

    /**
     * @return string
     * @throws SymfonyInvalidArgumentException
     * @throws InvalidHookType
     */
    private function getHookTypeArgument(): string
    {
        if (isset($this->given_hook_type)) {
            return $this->given_hook_type;
        }

        return $this->given_hook_type = $this->validateHookTypeArgument();
    }

    /**
     * @return bool
     * @throws SymfonyInvalidArgumentException
     */
    private function isDiffMode(): bool
    {
        return (bool)$this->input->getOption(self::DIFF_MODE);
    }

    /**
     * @return array<string|int>[]
     * @throws SymfonyInvalidArgumentException
     * @throws InvalidHookType
     */
    private function prepareTableContent(): array
    {
        $table_content = [];

        foreach ($this->retrieveHooksList() as $hook_name => $how_many_times_fired) {
            $table_content[] = [$hook_name, $how_many_times_fired];
        }

        return $table_content;
    }

    /**
     * @return void
     * @throws SymfonyInvalidArgumentException
     * @throws InvalidHookType
     * @throws InvalidArgumentException
     */
    private function resolveDiffMode(): void
    {
        $missing_in_framework_enum = [];

        /** @var Hook $hookEnum */
        $hookEnum = match ($this->getHookTypeArgument()) {
            Type::action->name => Action::class,
            Type::filter->name => Filter::class,
        };

        foreach ($this->retrieveHooksList() as $hook_name => $how_many_times_fired) {
            if ($hookEnum::tryFrom($hook_name) === null) {
                $missing_in_framework_enum[] = [$hook_name];
            }
        }

        $this->writeTable(
            [Str::titleCase($this->getHookTypeArgument()) . ' hook names missing from Enum'],
            $missing_in_framework_enum
        );
    }

    /**
     * @return array<string, int>
     * @throws SymfonyInvalidArgumentException
     * @throws InvalidHookType
     */
    private function retrieveHooksList(): array
    {
        $global_variable_name = "wp_{$this->getHookTypeArgument()}s";

        global $$global_variable_name;

        return $$global_variable_name;
    }

    /**
     * @return string
     * @throws InvalidHookType
     * @throws SymfonyInvalidArgumentException
     */
    private function validateHookTypeArgument(): string
    {
        $given_hook_type = Str::lower((string)$this->input->getArgument(self::HOOK_TYPE_ARGUMENT_NAME));

        if (!in_array($given_hook_type, Type::stringCasesList())) {
            throw new InvalidHookType($given_hook_type);
        }

        return $given_hook_type;
    }

    private function writeTable(array $headers, array $rows): void
    {
        $this->mountTable()
            ->setHeaders($headers)
            ->setRows($rows)
            ->render();
    }
}
