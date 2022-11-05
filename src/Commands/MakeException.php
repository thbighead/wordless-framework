<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Wordless\Abstractions\StubMounters\ExceptionStubMounter;
use Wordless\Adapters\WordlessCommand;
use Wordless\Exceptions\FailedToCopyStub;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;

class MakeException extends WordlessCommand
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
        return 'Create a exception.';
    }

    protected function help(): string
    {
        return 'Creates a exception script file using its class name as base.';
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
        $exception_class_name = ucfirst($this->input->getArgument(self::EXCEPTION_CLASS_ARGUMENT_NAME));

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
