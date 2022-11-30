<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Wordless\Abstractions\StubMounters\ControllerStubMounter;
use Wordless\Adapters\WordlessCommand;
use Wordless\Exceptions\FailedToCopyStub;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class MakeController extends WordlessCommand
{
    protected static $defaultName = 'make:controller';

    private const CONTROLLER_CLASS_ARGUMENT_NAME = 'PascalCasedControllerClass';

    protected function arguments(): array
    {
        return [
            [
                self::ARGUMENT_DESCRIPTION_FIELD =>
                    'The class name of your new controller file in pascal case.',
                self::ARGUMENT_MODE_FIELD => InputArgument::REQUIRED,
                self::ARGUMENT_NAME_FIELD => self::CONTROLLER_CLASS_ARGUMENT_NAME,
            ],
        ];
    }

    protected function description(): string
    {
        return 'Creates a REST API controller.';
    }

    protected function help(): string
    {
        return 'Creates an API controller file using based on its class name to control custom REST API endpoints declared in it.';
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
        $controller_class_name = Str::pascalCase($this->input->getArgument(self::CONTROLLER_CLASS_ARGUMENT_NAME));

        $this->wrapScriptWithMessages(
            "Creating $controller_class_name...",
            function () use ($controller_class_name) {
                (new ControllerStubMounter(ProjectPath::controllers() . "/$controller_class_name.php"))
                    ->setReplaceContentDictionary(['DummyController' => $controller_class_name])
                    ->mountNewFile();
            }
        );

        return Command::SUCCESS;
    }
}
