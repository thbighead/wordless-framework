<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Makers;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Makers\MakeProvider\Exceptions\FailedToCreateProviderClassFile;
use Wordless\Application\Commands\Makers\MakeProvider\Exceptions\FailedToMountProviderClassName;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Traits\Internal\Exceptions\FailedToCreateInflector;
use Wordless\Application\Mounters\Stub\CustomTaxonomyStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class MakeProvider extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'make:provider';
    private const PROVIDER_CLASS_ARGUMENT_NAME = 'PascalCasedCustomTaxonomyClass';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [
            ArgumentDTO::make(
                self::PROVIDER_CLASS_ARGUMENT_NAME,
                'The class name of your new Provider file in pascal case.',
                ArgumentMode::required
            ),
        ];
    }

    protected function description(): string
    {
        return 'Creates a Provider.';
    }

    protected function help(): string
    {
        return 'Creates a Provider file based on its class name.';
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
     * @throws FailedToCreateProviderClassFile
     * @throws FailedToMountProviderClassName
     */
    protected function runIt(): int
    {
        try {
            $provider_class_name = Str::pascalCase(
                $this->input->getArgument(self::PROVIDER_CLASS_ARGUMENT_NAME)
            );
        } catch (FailedToCreateInflector|InvalidArgumentException $exception) {
            throw new FailedToMountProviderClassName($exception);
        }

        try {
            $this->wrapScriptWithMessages(
                "Creating $provider_class_name...",
                function () use ($provider_class_name) {
                    CustomTaxonomyStubMounter::make(
                        ProjectPath::providers() . "/$provider_class_name.php"
                    )->setReplaceContentDictionary(['DummyProviderClass' => $provider_class_name])->mountNewFile();
                }
            );
        } catch (FailedToCopyStub|PathNotFoundException $exception) {
            throw new FailedToCreateProviderClassFile($exception);
        }

        return Command::SUCCESS;
    }
}
