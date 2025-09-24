<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Makers;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException as SymfonyInvalidArgumentException;
use Wordless\Application\Commands\Makers\Exceptions\FailedToMake;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Traits\Internal\Exceptions\FailedToCreateInflector;
use Wordless\Application\Mounters\Stub\ControllerStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;
use Wordless\Infrastructure\Wordpress\ApiController;
use Wordless\Wordpress\Models\Role;
use Wordless\Wordpress\Models\Role\Enums\DefaultRole;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToFindRole;

class MakeController extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'make:controller';
    private const CONTROLLER_CLASS_ARGUMENT_NAME = 'PascalCasedControllerClass';
    private const NO_PERMISSIONS_MODE = 'no-permissions';

    protected function arguments(): array
    {
        return [
            ArgumentDTO::make(
                self::CONTROLLER_CLASS_ARGUMENT_NAME,
                'The class name of your new controller file in pascal case.',
                ArgumentMode::required
            ),
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
        return [
            OptionDTO::make(
                self::NO_PERMISSIONS_MODE,
                'Don\'t auto register Controller permissions into admin role.',
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
            $controller_suffix = 'Controller';
            $controllerClassNameSubject = Str::of(
                $this->input->getArgument(self::CONTROLLER_CLASS_ARGUMENT_NAME)
            )->pascalCase()->finishWith($controller_suffix);
            $controller_class_name = (string)$controllerClassNameSubject;
            $resource_name = (string)$controllerClassNameSubject->beforeLast($controller_suffix)->snakeCase();

            $this->wrapScriptWithMessages(
                "Creating $controller_class_name...",
                function () use ($controller_class_name, $resource_name) {
                    ControllerStubMounter::make(ProjectPath::controllers() . "/$controller_class_name.php")
                        ->setReplaceContentDictionary([
                            'DummyController' => $controller_class_name,
                            'dummy_resource' => $resource_name,
                        ])->mountNewFile();
                }
            );

            $this->resolveNoPermissionsMode($controller_class_name);
        } catch (FailedToCopyStub
        |FailedToCreateInflector
        |FailedToFindRole
        |PathNotFoundException
        |SymfonyInvalidArgumentException $exception) {
            throw new FailedToMake('Controller', $exception);
        }

        return Command::SUCCESS;
    }

    private function isNoPermissionsMode(): bool
    {
        try {
            return (bool)$this->input->getOption(self::NO_PERMISSIONS_MODE);
        } catch (SymfonyInvalidArgumentException) {
            return false;
        }
    }

    /**
     * @param string $controller_class_name
     * @return void
     * @throws FailedToFindRole
     */
    private function resolveNoPermissionsMode(string $controller_class_name): void
    {
        if ($this->isNoPermissionsMode()) {
            return;
        }

        $this->wrapScriptWithMessages(
            "Registering $controller_class_name permissions to admin role...",
            function () use ($controller_class_name) {
                /** @var ApiController $controller_class_guessed_namespace */
                $controller_class_guessed_namespace = "App\\Controllers\\$controller_class_name";

                $controller_class_guessed_namespace::getInstance()->registerCapabilitiesToRole(
                    Role::findOrFail(DefaultRole::admin->value)
                );
            }
        );
    }
}
