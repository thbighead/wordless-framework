<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Mounters\Stub\CommandStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class MakeCommand extends ConsoleCommand
{
    final public const COMMAND_NAME = 'make:command';
    private const COMMAND_CLASS_ARGUMENT_NAME = 'PascalCasedCommandClass';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [
            new ArgumentDTO(
                self::COMMAND_CLASS_ARGUMENT_NAME,
                'The class name of your new command file in pascal case.',
                ArgumentMode::required
            ),
        ];
    }

    protected function description(): string
    {
        return 'Creates a console command.';
    }

    protected function help(): string
    {
        return 'Creates a console command script file based on its class name which shall be listed into "php console" command.';
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
     * @throws PathNotFoundException
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     */
    protected function runIt(): int
    {
        $command_class_name = Str::pascalCase($this->input->getArgument(self::COMMAND_CLASS_ARGUMENT_NAME));

        $this->wrapScriptWithMessages(
            "Creating $command_class_name...",
            function () use ($command_class_name) {
                CommandStubMounter::make(ProjectPath::app() . "Commands/$command_class_name.php")
                    ->setReplaceContentDictionary(['DummyCommand' => $command_class_name])
                    ->mountNewFile();
            }
        );

        return Command::SUCCESS;
    }
}
