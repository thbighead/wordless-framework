<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Mounters\Stub\CommandStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class MakeCommand extends ConsoleCommand
{
    protected static $defaultName = 'make:command';

    private const COMMAND_CLASS_ARGUMENT_NAME = 'PascalCasedCommandClass';

    protected function arguments(): array
    {
        return [
            [
                self::ARGUMENT_DESCRIPTION_FIELD =>
                    'The class name of your new command file in pascal case.',
                self::ARGUMENT_MODE_FIELD => InputArgument::REQUIRED,
                self::ARGUMENT_NAME_FIELD => self::COMMAND_CLASS_ARGUMENT_NAME,
            ],
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

    protected function options(): array
    {
        return [];
    }

    /**
     * @return int
     * @throws FailedToCopyStub
     * @throws PathNotFoundException
     */
    protected function runIt(): int
    {
        $command_class_name = Str::pascalCase($this->input->getArgument(self::COMMAND_CLASS_ARGUMENT_NAME));

        $this->wrapScriptWithMessages(
            "Creating $command_class_name...",
            function () use ($command_class_name) {
                (new CommandStubMounter(ProjectPath::app() . "Commands/$command_class_name.php"))
                    ->setReplaceContentDictionary(['DummyCommand' => $command_class_name])
                    ->mountNewFile();
            }
        );

        return Command::SUCCESS;
    }
}
