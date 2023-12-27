<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Mounters\Stub\ControllerStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;
use Wordless\Infrastructure\Wordpress\ApiController;
use Wordless\Wordpress\Models\Role;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToFindRole;

class MakeController extends ConsoleCommand
{
    final public const COMMAND_NAME = 'make:controller';
    private const CONTROLLER_CLASS_ARGUMENT_NAME = 'PascalCasedControllerClass';
    private const NO_PERMISSIONS_MODE = 'no-permissions';

    protected static $defaultName = self::COMMAND_NAME;

    protected function arguments(): array
    {
        return [
            new ArgumentDTO(
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
            new OptionDTO(
                self::NO_PERMISSIONS_MODE,
                'Don\'t auto register CPT permissions into admin role.',
                mode: OptionMode::no_value
            ),
        ];
    }

    /**
     * @return int
     * @throws FailedToCopyStub
     * @throws PathNotFoundException
     * @throws FailedToCreateDirectory
     * @throws FailedToGetDirectoryPermissions
     */
    protected function runIt(): int
    {
        $controller_class_name = Str::pascalCase($this->input->getArgument(self::CONTROLLER_CLASS_ARGUMENT_NAME));

        $this->wrapScriptWithMessages(
            "Creating $controller_class_name...",
            function () use ($controller_class_name) {
                ControllerStubMounter::make(ProjectPath::controllers() . "/$controller_class_name.php")
                    ->setReplaceContentDictionary(['DummyController' => $controller_class_name])
                    ->mountNewFile();
            }
        );

        $this->resolveNoPermissionsMode($controller_class_name);

        return Command::SUCCESS;
    }

    private function isNoPermissionsMode(): bool
    {
        return (bool)$this->input->getOption(self::NO_PERMISSIONS_MODE);
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
                $controller_class_guessed_namespace::getInstance()
                    ->registerCapabilitiesToRole(Role::find(Role::ADMIN));
            }
        );
    }
}
