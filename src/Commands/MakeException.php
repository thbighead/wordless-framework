<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Wordless\Abstractions\StubMounters\ExceptionStubMounter;
use Wordless\Adapters\ConsoleCommand;
use Wordless\Exceptions\FailedToCopyStub;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class MakeException extends ConsoleCommand
{
    protected static $defaultName = 'make:exception';

    private const EXCEPTION_CLASS_ARGUMENT_NAME = 'PascalCasedExceptionClass';

    protected function arguments(): array
    {
        return [
            [
                self::ARGUMENT_DESCRIPTION_FIELD =>
                    'The class name of your new exception file in pascal case.',
                self::ARGUMENT_MODE_FIELD => InputArgument::REQUIRED,
                self::ARGUMENT_NAME_FIELD => self::EXCEPTION_CLASS_ARGUMENT_NAME,
            ],
        ];
    }

    protected function description(): string
    {
        return 'Creates an exception class.';
    }

    protected function help(): string
    {
        return 'Creates an exception file based on its class name.';
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
        $exception_class_name = Str::pascalCase($this->input->getArgument(self::EXCEPTION_CLASS_ARGUMENT_NAME));

        $this->wrapScriptWithMessages(
            "Creating $exception_class_name...",
            function () use ($exception_class_name) {
                (new ExceptionStubMounter(ProjectPath::exceptions() . "/$exception_class_name.php"))
                    ->setReplaceContentDictionary(['DummyException' => $exception_class_name])
                    ->mountNewFile();
            }
        );

        return Command::SUCCESS;
    }
}
