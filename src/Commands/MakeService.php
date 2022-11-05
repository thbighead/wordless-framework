<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Wordless\Abstractions\StubMounters\ServiceStubMounter;
use Wordless\Adapters\WordlessCommand;
use Wordless\Exceptions\FailedToCopyStub;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class MakeService extends WordlessCommand
{
    protected static $defaultName = 'make:service';

    private const SERVICE_CLASS_ARGUMENT_NAME = 'PascalCasedCommandClass';

    protected function arguments(): array
    {
        return [
            [
                self::ARGUMENT_DESCRIPTION_FIELD =>
                    'The class name of your new service file in pascal case.',
                self::ARGUMENT_MODE_FIELD => InputArgument::REQUIRED,
                self::ARGUMENT_NAME_FIELD => self::SERVICE_CLASS_ARGUMENT_NAME,
            ],
        ];
    }

    protected function description(): string
    {
        return 'Creates a service.';
    }

    protected function help(): string
    {
        return 'Creates a generic service file based on its class name.';
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
        $service_class_name = Str::pascalCase($this->input->getArgument(self::SERVICE_CLASS_ARGUMENT_NAME));

        $this->wrapScriptWithMessages(
            "Creating $service_class_name...",
            function () use ($service_class_name) {
                (new ServiceStubMounter(ProjectPath::services() . "/$service_class_name.php"))
                    ->setReplaceContentDictionary(['DummyService' => $service_class_name])
                    ->mountNewFile();
            }
        );

        return Command::SUCCESS;
    }
}
