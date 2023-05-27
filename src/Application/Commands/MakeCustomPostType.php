<?php

namespace Wordless\Application\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Mounters\Stub\CustomPostTypeStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\CustomPost;
use Wordless\Infrastructure\CustomPost\Traits\Register\Validation\Exceptions\InvalidCustomPostTypeKey;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToCreateRole;
use Wordless\Wordpress\Models\Role\Exceptions\FailedToFindRole;
use Wordless\Wordpress\RolesList;

class MakeCustomPostType extends ConsoleCommand
{
    use LoadWpConfig;

    protected static $defaultName = 'make:cpt';

    private const CONTROLLER_OPTION = 'controller';
    private const CUSTOM_POST_TYPE_CLASS_ARGUMENT_NAME = 'PascalCasedCustomPostTypeClass';
    private const NO_PERMISSIONS_MODE = 'no-permissions';

    protected function arguments(): array
    {
        return [
            [
                self::ARGUMENT_DESCRIPTION_FIELD =>
                    'The class name of your new Custom Post Type file in pascal case.',
                self::ARGUMENT_MODE_FIELD => InputArgument::REQUIRED,
                self::ARGUMENT_NAME_FIELD => self::CUSTOM_POST_TYPE_CLASS_ARGUMENT_NAME,
            ],
        ];
    }

    protected function description(): string
    {
        return 'Creates a Custom Post Type.';
    }

    protected function help(): string
    {
        return
            'Creates a Custom Post Type file based on its class name. Its permissions shall be automatically registered as capable for admin role.';
    }

    protected function options(): array
    {
        return [
            [
                self::OPTION_NAME_FIELD => self::CONTROLLER_OPTION,
                self::OPTION_MODE_FIELD => InputOption::VALUE_REQUIRED,
                self::OPTION_DESCRIPTION_FIELD =>
                    'The Controller class name to be used by REST API to serve this resource',
            ],
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
     * @throws FailedToCreateRole
     * @throws InvalidCustomPostTypeKey
     * @throws PathNotFoundException
     * @throws FailedToFindRole
     */
    protected function runIt(): int
    {
        $custom_post_type_class_name = Str::pascalCase(
            $this->input->getArgument(self::CUSTOM_POST_TYPE_CLASS_ARGUMENT_NAME)
        );

        $this->wrapScriptWithMessages(
            "Creating $custom_post_type_class_name...",
            function () use ($custom_post_type_class_name) {
                (new CustomPostTypeStubMounter(
                    ProjectPath::customPostTypes() . "/$custom_post_type_class_name.php")
                )->setReplaceContentDictionary(
                    $this->mountStubContentReplacementDictionary($custom_post_type_class_name)
                )->mountNewFile();
            }
        );

        $this->resolveNoPermissionsMode($custom_post_type_class_name);

        return Command::SUCCESS;
    }

    private function getCustomControllerMode(): ?string
    {
        $custom_controller_class_name = $this->input->getOption(self::CONTROLLER_OPTION);

        return $custom_controller_class_name ?: null;
    }

    private function isNoPermissionsMode(): bool
    {
        return (bool)$this->input->getOption(self::NO_PERMISSIONS_MODE);
    }

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
     * @throws FailedToCreateRole
     * @throws InvalidCustomPostTypeKey
     * @throws PathNotFoundException
     * @throws FailedToFindRole
     */
    private function resolveNoPermissionsMode(string $custom_post_type_class_name)
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
                RolesList::sync();
            }
        );
    }
}
