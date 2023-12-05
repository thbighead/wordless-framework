<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Mounters\Stub\HookerStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class MakeHooker extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'make:hooker';
    private const HOOKER_CLASS_ARGUMENT_NAME = 'PascalCasedHookerClass';

    protected static $defaultName = self::COMMAND_NAME;

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [
            new ArgumentDTO(
                self::HOOKER_CLASS_ARGUMENT_NAME,
                'The class name of your new hooker file in pascal case.',
                ArgumentMode::required
            ),
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
