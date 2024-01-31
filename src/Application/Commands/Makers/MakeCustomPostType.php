<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Makers;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException as SymfonyInvalidArgumentException;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Mounters\Stub\CustomPostTypeStubMounter;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO\Enums\OptionMode;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;
use Wordless\Infrastructure\Wordpress\CustomPost;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Exceptions\CustomPostTypeRegistrationFailed;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\InvalidCustomPostTypeKey;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostTypeKey;
use Wordless\Wordpress\Models\Role;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToCreateRole;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToFindRole;

class MakeCustomPostType extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'make:cpt';
    private const CONTROLLER_OPTION = 'controller';
    private const CUSTOM_POST_TYPE_CLASS_ARGUMENT_NAME = 'PascalCasedCustomPostTypeClass';
    private const NO_PERMISSIONS_MODE = 'no-permissions';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [
            ArgumentDTO::make(
                self::CUSTOM_POST_TYPE_CLASS_ARGUMENT_NAME,
                'The class name of your new Custom Post Type file in pascal case.',
                ArgumentMode::required
            ),
        ];
    }

    protected function description(): string
    {
        return 'Creates a Custom Post Type.';
    }

    protected function help(): string
    {
        return 'Creates a Custom Post Type file based on its class name. Its permissions shall be automatically registered as capable for admin role.';
    }

    /**
     * @return OptionDTO[]
     */
    protected function options(): array
    {
        return [
            OptionDTO::make(
                self::CONTROLLER_OPTION,
                'The Controller class name to be used by REST API to serve this resource',
                mode: OptionMode::required_value
            ),
            OptionDTO::make(
                self::NO_PERMISSIONS_MODE,
                'Don\'t auto register CPT permissions into admin role.',
                mode: OptionMode::no_value,
            ),
        ];
    }

    /**
     * @return int
     * @throws CustomPostTypeRegistrationFailed
     * @throws FailedToCopyStub
     * @throws FailedToCreateDirectory
     * @throws FailedToCreateRole
     * @throws FailedToFindRole
     * @throws FailedToGetDirectoryPermissions
     * @throws InvalidArgumentException
     * @throws InvalidConfigKey
     * @throws InvalidCustomPostTypeKey
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     * @throws ReservedCustomPostTypeKey
     * @throws SymfonyInvalidArgumentException
     */
    protected function runIt(): int
    {
        $custom_post_type_class_name = Str::pascalCase(
            $this->input->getArgument(self::CUSTOM_POST_TYPE_CLASS_ARGUMENT_NAME)
        );

        $this->wrapScriptWithMessages(
            "Creating $custom_post_type_class_name...",
            function () use ($custom_post_type_class_name) {
                CustomPostTypeStubMounter::make(
                    ProjectPath::customPostTypes() . "/$custom_post_type_class_name.php")
                    ->setReplaceContentDictionary(
                        $this->mountStubContentReplacementDictionary($custom_post_type_class_name)
                    )->mountNewFile();
            }
        );

        $this->resolveNoPermissionsMode($custom_post_type_class_name);

        return Command::SUCCESS;
    }

    private function getCustomControllerMode(): ?string
    {
        try {
            $custom_controller_class_name = $this->input->getOption(self::CONTROLLER_OPTION);
        } catch (SymfonyInvalidArgumentException) {
            return null;
        }

        return $custom_controller_class_name ?: null;
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
     * @param string $custom_post_type_class_name
     * @return array<string, string>
     * @throws InvalidArgumentException
     */
    private function mountStubContentReplacementDictionary(string $custom_post_type_class_name): array
    {
        $content_replacement_dictionary = [
            'DummyCustomPostTypeClass' => $custom_post_type_class_name,
            'snake_cased_cpt_key' => Str::snakeCase($custom_post_type_class_name),
            'Title Cased Cpt Plural Name' => Str::plural(
                $title_cased_cpt_plural_name = Str::titleCase($custom_post_type_class_name)
            ),
            'Title Cased Cpt Singular Name' => Str::singular($title_cased_cpt_plural_name),
        ];

        if ($custom_controller_class_name = $this->getCustomControllerMode()) {
            $content_replacement_dictionary['return null; // automagically controlled by WP'] =
                "return $custom_controller_class_name::class;";
            $content_replacement_dictionary[$original_uses = 'use Wordless\Adapters\WordlessCustomPost;'] =
                $original_uses . PHP_EOL . "use App\\Controllers\\$custom_controller_class_name;";
        }

        return $content_replacement_dictionary;
    }

    /**
     * @param string $custom_post_type_class_name
     * @return void
     * @throws CustomPostTypeRegistrationFailed
     * @throws FailedToCreateRole
     * @throws FailedToFindRole
     * @throws InvalidArgumentException
     * @throws InvalidConfigKey
     * @throws InvalidCustomPostTypeKey
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     * @throws ReservedCustomPostTypeKey
     */
    private function resolveNoPermissionsMode(string $custom_post_type_class_name): void
    {
        if ($this->isNoPermissionsMode()) {
            return;
        }

        $this->wrapScriptWithMessages(
            "Registering $custom_post_type_class_name permissions to admin role...",
            function () use ($custom_post_type_class_name) {
                /** @var CustomPost $custom_post_type_class_guessed_namespace */
                $custom_post_type_class_guessed_namespace = "App\\CustomPostTypes\\$custom_post_type_class_name";
                $custom_post_type_class_guessed_namespace::register();
                Role::sync();
            }
        );
    }
}
