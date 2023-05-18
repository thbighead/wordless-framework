<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Mounters\Stub\HookerStubMounter;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class MakeHooker extends ConsoleCommand
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
        return 'Creates a hooker script.';
    }

    protected function help(): string
    {
        return 'Creates a hooker script file based on its class name.';
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
        $hooker_class_name = Str::pascalCase($this->input->getArgument(self::HOOKER_CLASS_ARGUMENT_NAME));

        $this->wrapScriptWithMessages(
            "Creating $hooker_class_name...",
            function () use ($hooker_class_name) {
                (new HookerStubMounter(ProjectPath::hookers() . "/$hooker_class_name.php"))
                    ->setReplaceContentDictionary(['DummyHooker' => $hooker_class_name])
                    ->mountNewFile();
            }
        );

        return Command::SUCCESS;
    }
}
