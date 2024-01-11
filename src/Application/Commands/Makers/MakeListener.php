<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Makers;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException as SymfonyInvalidArgumentException;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Mounters\Stub\ActionListenerStubMounter;
use Wordless\Application\Mounters\Stub\FilterListenerStubMounter;
use Wordless\Application\Mounters\Stub\ListenerStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;
use Wordless\Infrastructure\Mounters\StubMounter;
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
            ArgumentDTO::make(
                self::LISTENER_CLASS_ARGUMENT_NAME,
                'The class name of your new hooker file in pascal case.',
                ArgumentMode::required
            ),
        ];
    }

    protected function description(): string
    {
        return 'Creates a hook listener.';
    }

    protected function help(): string
    {
        return 'If no type is defined through options, a generic listener class is created.';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [
            OptionDTO::make(
                self::LISTENER_FILTER_MODE,
                'Generates a filter listener.',
                mode: OptionMode::no_value
            ),
            OptionDTO::make(
                self::LISTENER_ACTION_MODE,
                'Generates an action listener.',
                mode: OptionMode::no_value
            ),
        ];
    }

    /**
     * @return int
     * @throws FailedToCopyStub
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     * @throws PathNotFoundException
     * @throws InvalidArgumentException
     * @throws SymfonyInvalidArgumentException
     */
    protected function runIt(): int
    {
        $listenerStubMounter = $this->mountStubMounter(
            $listener_class_name = Str::pascalCase($this->input->getArgument(self::LISTENER_CLASS_ARGUMENT_NAME))
        );

        $this->wrapScriptWithMessages(
            "Creating $listener_class_name...",
            function () use ($listenerStubMounter) {
                $listenerStubMounter->mountNewFile();
            }
        );

        return Command::SUCCESS;
    }

    /**
     * @return bool
     * @throws SymfonyInvalidArgumentException
     */
    private function isFilterListener(): bool
    {
        return (bool)$this->input->getOption(self::LISTENER_FILTER_MODE);
    }

    /**
     * @param string $listener_class_name
     * @return StubMounter
     * @throws PathNotFoundException
     * @throws SymfonyInvalidArgumentException
     */
    private function mountStubMounter(string $listener_class_name): StubMounter
    {
        $mounter_new_file_path = ProjectPath::listeners() . "/$listener_class_name.php";

        switch (true) {
            case $this->input->getOption(self::LISTENER_ACTION_MODE):
                $stubMounter = ActionListenerStubMounter::make($mounter_new_file_path);
                $listener_class_name_key = 'DummyActionListener';
                break;
            case $this->input->getOption(self::LISTENER_FILTER_MODE):
                $stubMounter = FilterListenerStubMounter::make($mounter_new_file_path);
                $listener_class_name_key = 'DummyFilterListener';
                break;
            default:
                $stubMounter = ListenerStubMounter::make($mounter_new_file_path);
                $listener_class_name_key = 'DummyListener';
        }

        return $stubMounter->setReplaceContentDictionary([$listener_class_name_key => $listener_class_name]);
    }
}
