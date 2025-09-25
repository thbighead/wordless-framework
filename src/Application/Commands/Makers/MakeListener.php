<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Makers;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException as SymfonyInvalidArgumentException;
use Wordless\Application\Commands\Makers\Exceptions\FailedToMake;
use Wordless\Application\Commands\Makers\MakeListener\Exceptions\FailedToDetermineStubMounter;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Traits\Internal\Exceptions\FailedToCreateInflector;
use Wordless\Application\Mounters\Stub\ActionListenerStubMounter;
use Wordless\Application\Mounters\Stub\FilterListenerStubMounter;
use Wordless\Application\Mounters\Stub\ListenerStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;
use Wordless\Infrastructure\Mounters\StubMounter;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class MakeListener extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'make:listener';
    private const ARGUMENT_NAME_LISTENER_CLASS = 'PascalCasedListenerClass';
    private const ARGUMENT_NAME_REGISTER_FUNCTION = 'camelCasedMethodName';
    private const MODE_LISTENER_ACTION = 'action';
    private const MODE_LISTENER_FILTER = 'filter';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [
            ArgumentDTO::make(
                self::ARGUMENT_NAME_LISTENER_CLASS,
                'The class name of your new listener file in pascal case.',
                ArgumentMode::required
            ),
            ArgumentDTO::make(
                self::ARGUMENT_NAME_REGISTER_FUNCTION,
                'The method name registered for your new listener file in camel case.',
                ArgumentMode::optional
            ),
        ];
    }

    protected function description(): string
    {
        return 'Creates a hook listener.';
    }

    protected function help(): string
    {
        return 'If no type is defined through options, a generic listener class is created.';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [
            OptionDTO::make(
                self::MODE_LISTENER_FILTER,
                'Generates a filter listener.',
                mode: OptionMode::no_value
            ),
            OptionDTO::make(
                self::MODE_LISTENER_ACTION,
                'Generates an action listener.',
                mode: OptionMode::no_value
            ),
        ];
    }

    /**
     * @return int
     * @throws FailedToMake
     */
    protected function runIt(): int
    {
        try {
            $listenerStubMounter = $this->mountStubMounter(
                $listener_class_name = Str::pascalCase($this->input->getArgument(self::ARGUMENT_NAME_LISTENER_CLASS))
            );

            $this->wrapScriptWithMessages(
                "Creating $listener_class_name...",
                function () use ($listenerStubMounter) {
                    $listenerStubMounter->mountNewFile();
                }
            );
        } catch (FailedToCopyStub
        |FailedToCreateInflector
        |FailedToDetermineStubMounter
        |SymfonyInvalidArgumentException $exception) {
            throw new FailedToMake(
                isset($listenerStubMounter) ? 'Listener with ' . $listenerStubMounter::class : 'Listener',
                $exception
            );
        }

        return Command::SUCCESS;
    }

    /**
     * @param string $listener_class_name
     * @return StubMounter
     * @throws FailedToDetermineStubMounter
     */
    private function mountStubMounter(string $listener_class_name): StubMounter
    {
        try {
            $mounter_new_file_path = ProjectPath::listeners() . "/$listener_class_name.php";

            switch (true) {
                case $this->input->getOption(self::MODE_LISTENER_ACTION):
                    $stubMounter = ActionListenerStubMounter::make($mounter_new_file_path);
                    $listener_class_name_key = 'DummyActionListener';
                    break;
                case $this->input->getOption(self::MODE_LISTENER_FILTER):
                    $stubMounter = FilterListenerStubMounter::make($mounter_new_file_path);
                    $listener_class_name_key = 'DummyFilterListener';
                    break;
                default:
                    $stubMounter = ListenerStubMounter::make($mounter_new_file_path);
                    $listener_class_name_key = 'DummyListener';
            }

            $replace_content_dictionary = [$listener_class_name_key => $listener_class_name];

            if (!empty($register_function = $this->input->getArgument(self::ARGUMENT_NAME_REGISTER_FUNCTION))) {
                $replace_content_dictionary['myCustomFunction'] = Str::camelCase($register_function);
            }
        } catch (FailedToCreateInflector|PathNotFoundException|SymfonyInvalidArgumentException $exception) {
            throw new FailedToDetermineStubMounter($exception);
        }

        return $stubMounter->setReplaceContentDictionary($replace_content_dictionary);
    }
}
