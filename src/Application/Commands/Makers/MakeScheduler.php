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
use Wordless\Application\Mounters\Stub\SchedulerStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;
use Wordless\Infrastructure\Mounters\StubMounter;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class MakeScheduler extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'make:scheduler';
    private const SCHEDULER_CLASS_ARGUMENT_NAME = 'PascalCasedListenerClass';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [
            ArgumentDTO::make(
                self::SCHEDULER_CLASS_ARGUMENT_NAME,
                'The class name of your new scheduler file in pascal case.',
                ArgumentMode::required
            ),
        ];
    }

    protected function description(): string
    {
        return 'Creates a scheduler class.';
    }

    protected function help(): string
    {
        return 'Creates a scheduler class to interact with wordpress schedule logic.';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [];
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
        $scheduler_class_name = Str::pascalCase($this->input->getArgument(self::SCHEDULER_CLASS_ARGUMENT_NAME));

        $this->wrapScriptWithMessages(
            "Creating $scheduler_class_name...",
            function () use ($scheduler_class_name) {
                SchedulerStubMounter::make(ProjectPath::schedulers() . "/$scheduler_class_name.php")
                    ->setReplaceContentDictionary([
                        'DummyScheduler' => $scheduler_class_name,
                    ])->mountNewFile();
            }
        );

        return Command::SUCCESS;
    }
}
