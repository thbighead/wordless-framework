<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Mounters\Stub\WordlessPluginStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class GenerateWordlessPluginLoader extends ConsoleCommand
{
    final public const COMMAND_NAME = 'mup:loader';

    protected static $defaultName = self::COMMAND_NAME;

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Generate WordPress Must Use Plugins loader from stub.';
    }

    protected function help(): string
    {
        return 'This is better than looping through directories everytime the project runs.';
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
        $this->wrapScriptWithMessages('Generating Wordless must use plugin loader...', function () {
            (new WordlessPluginStubMounter(
                ProjectPath::wpMustUsePlugins() . DIRECTORY_SEPARATOR . 'wordless-plugin.php'
            ))->mountNewFile();
        });

        return Command::SUCCESS;
    }
}
