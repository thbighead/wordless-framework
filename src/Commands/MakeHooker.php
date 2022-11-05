<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Wordless\Abstractions\Migrations\Script;
use Wordless\Abstractions\StubMounters\MigrationStubMounter;
use Wordless\Adapters\WordlessCommand;
use Wordless\Contracts\Command\LoadWpConfig;
use Wordless\Exceptions\FailedToCopyStub;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class MakeHooker extends WordlessCommand
{
    use LoadWpConfig;

    protected static $defaultName = 'make:hooker';

    private const HOOKER_CLASS_ARGUMENT_NAME = 'PascalCasedHookerClass';

    protected function arguments(): array
    {
        return [
            [
                self::ARGUMENT_DESCRIPTION_FIELD =>
                    'The class name of your new hooker file in pascal case.',
                self::ARGUMENT_MODE_FIELD => InputArgument::REQUIRED,
                self::ARGUMENT_NAME_FIELD => self::HOOKER_CLASS_ARGUMENT_NAME,
            ],
        ];
    }

    protected function description(): string
    {
        return 'Create a hooker script.';
    }

    protected function help(): string
    {
        return 'Creates a hooker script file using its class name as base.';
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
        $hooker_class_name = $this->input->getArgument(self::HOOKER_CLASS_ARGUMENT_NAME);
        var_dump($hooker_class_name);
        
        $this->wrapScriptWithMessages(
            "Creating $hooker_class_name...",
            function () use ($hooker_class_name) {
                (new HookerStubMounter(ProjectPath::hookers() . "/$hooker_class_name"))
                    ->setReplaceContentDictionary(['DummyHooker' => $hooker_class_name])
                    ->mountNewFile();
            }
        );

        return Command::SUCCESS;
    }
}
