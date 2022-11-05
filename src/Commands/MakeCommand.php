<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Wordless\Abstractions\StubMounters\CommandStubMounter;
use Wordless\Adapters\WordlessCommand;
use Wordless\Exceptions\FailedToCopyStub;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

class MakeCommand extends WordlessCommand
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
        return 'Create a command.';
    }

    protected function help(): string
    {
        return 'Creates a command script file using its class name as base.';
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
        $command_class_name = ucfirst($this->input->getArgument(self::COMMAND_CLASS_ARGUMENT_NAME));

        $this->wrapScriptWithMessages(
            "Creating $command_class_name...",
            function () use ($command_class_name) {
                (new CommandStubMounter(ProjectPath::appCommands() . "/$command_class_name.php"))
                    ->setReplaceContentDictionary(['DummyCommand' => $command_class_name])
                    ->mountNewFile();
            }
        );

        return Command::SUCCESS;
    }
}
