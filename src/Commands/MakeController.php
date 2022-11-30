<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Wordless\Abstractions\StubMounters\ControllerStubMounter;
use Wordless\Adapters\Role;
use Wordless\Adapters\WordlessCommand;
use Wordless\Adapters\WordlessController;
use Wordless\Exceptions\FailedToCopyStub;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Exceptions\WordPressFailedToFindRole;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

class MakeController extends WordlessCommand
{
    protected static $defaultName = 'make:controller';

    private const CONTROLLER_CLASS_ARGUMENT_NAME = 'PascalCasedControllerClass';
    private const NO_PERMISSIONS_MODE = 'no-permissions';

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
        return [
            [
                self::OPTION_NAME_FIELD => self::NO_PERMISSIONS_MODE,
                self::OPTION_SHORTCUT_FIELD => 'N',
                self::OPTION_MODE_FIELD => InputOption::VALUE_NONE,
                self::OPTION_DESCRIPTION_FIELD => 'Don\'t auto register CPT permissions into admin role.',
            ],
        ];
    }

    /**
     * @return int
     * @throws FailedToCopyStub
     * @throws PathNotFoundException
     * @throws WordPressFailedToFindRole
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
     * @throws WordPressFailedToFindRole
     */
    private function resolveNoPermissionsMode(string $controller_class_name)
    {
        if ($this->isNoPermissionsMode()) {
            return;
        }

        $this->wrapScriptWithMessages(
            "Registering $controller_class_name permissions to admin role...",
            function () use ($controller_class_name) {
                /** @var WordlessController $controller_class_guessed_namespace */
                $controller_class_guessed_namespace = "App\\Controllers\\$controller_class_name";
                $controller_class_guessed_namespace::getInstance()
                    ->registerCapabilitiesToRole(Role::find(Role::ADMIN));
            }
        );
    }
}
