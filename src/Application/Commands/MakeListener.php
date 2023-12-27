<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Mounters\Stub\ActionListenerStubMounter;
use Wordless\Application\Mounters\Stub\FilterListenerStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class MakeListener extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'make:listener';
    private const LISTENER_CLASS_ARGUMENT_NAME = 'PascalCasedListenerClass';
    const LISTENER_ACTION_MODE = 'action';
    const LISTENER_FILTER_MODE = 'filter';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [
            new ArgumentDTO(
                self::LISTENER_CLASS_ARGUMENT_NAME,
                'The class name of your new hooker file in pascal case.',
                ArgumentMode::required
            ),
        ];
    }

    protected function description(): string
    {
        return 'Creates a listener script.';
    }

    protected function help(): string
    {
        return 'Creates a listener script file based on its class name.';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [
            new OptionDTO(
                self::LISTENER_FILTER_MODE,
                'Generates a filter listener instead of action.',
                mode: OptionMode::no_value
            ),
        ];
    }

    /**
     * @return int
     * @throws FailedToCopyStub
     * @throws PathNotFoundException
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     */
    protected function runIt(): int
    {
        $listener_class_name = Str::pascalCase($this->input->getArgument(self::LISTENER_CLASS_ARGUMENT_NAME));
        $listener_stub_type = $this->isFilterListener() ?
            FilterListenerStubMounter::class : ActionListenerStubMounter::class;

        $this->wrapScriptWithMessages(
            "Creating $listener_class_name...",
            function () use ($listener_class_name, $listener_stub_type) {
                $listener_stub_type::make(ProjectPath::listeners() . "/$listener_class_name.php")
                    ->setReplaceContentDictionary(['DummyListener' => $listener_class_name])
                    ->mountNewFile();
            }
        );

        return Command::SUCCESS;
    }

    private function isFilterListener(): bool
    {
        return (bool)$this->input->getOption(self::LISTENER_FILTER_MODE);
    }
}
