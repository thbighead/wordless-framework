<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Makers;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Makers\MakeCustomTaxonomy\Exceptions\FailedToCreateCustomTaxonomyClassFile;
use Wordless\Application\Commands\Makers\MakeCustomTaxonomy\Exceptions\FailedToCreateCustomTaxonomyDictionaryClassFile;
use Wordless\Application\Commands\Makers\MakeCustomTaxonomy\Exceptions\FailedToMountCustomTaxonomyClassName;
use Wordless\Application\Commands\Traits\LoadWpConfig;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Traits\Internal\Exceptions\FailedToCreateInflector;
use Wordless\Application\Mounters\Stub\CustomTaxonomyDictionaryStubMounter;
use Wordless\Application\Mounters\Stub\CustomTaxonomyStubMounter;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO\Enums\ArgumentMode;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class MakeCustomTaxonomy extends ConsoleCommand
{
    use LoadWpConfig;

    final public const COMMAND_NAME = 'make:taxonomy';
    private const CUSTOM_TAXONOMY_CLASS_ARGUMENT_NAME = 'PascalCasedCustomTaxonomyClass';

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [
            ArgumentDTO::make(
                self::CUSTOM_TAXONOMY_CLASS_ARGUMENT_NAME,
                'The class name of your new Custom Taxonomy file in pascal case.',
                ArgumentMode::required
            ),
        ];
    }

    protected function description(): string
    {
        return 'Creates a Custom Taxonomy.';
    }

    protected function help(): string
    {
        return 'Creates a Custom Taxonomy file based on its class name.';
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
     * @throws FailedToCreateCustomTaxonomyClassFile
     * @throws FailedToCreateCustomTaxonomyDictionaryClassFile
     * @throws FailedToMountCustomTaxonomyClassName
     */
    protected function runIt(): int
    {
        try {
            $custom_taxonomy_class_name = Str::pascalCase(
                $this->input->getArgument(self::CUSTOM_TAXONOMY_CLASS_ARGUMENT_NAME)
            );
        } catch (FailedToCreateInflector|InvalidArgumentException $exception) {
            throw new FailedToMountCustomTaxonomyClassName($exception);
        }

        try {
            $this->wrapScriptWithMessages(
                "Creating $custom_taxonomy_class_name...",
                function () use ($custom_taxonomy_class_name) {
                    CustomTaxonomyStubMounter::make(
                        ProjectPath::customTaxonomies() . "/$custom_taxonomy_class_name.php"
                    )->setReplaceContentDictionary([
                        'DummyCustomTaxonomyClass' => $custom_taxonomy_class_name,
                        'snake_cased_taxonomy_key' => Str::snakeCase($custom_taxonomy_class_name),
                    ])->mountNewFile();
                }
            );
        } catch (FailedToCopyStub|PathNotFoundException $exception) {
            throw new FailedToCreateCustomTaxonomyClassFile($exception);
        }

        try {
            $this->wrapScriptWithMessages(
                "Creating dictionary of $custom_taxonomy_class_name...",
                function () use ($custom_taxonomy_class_name) {
                    CustomTaxonomyDictionaryStubMounter::make(
                        ProjectPath::customTaxonomies() . "/$custom_taxonomy_class_name/Dictionary.php"
                    )->setReplaceContentDictionary([
                        'DummyCustomTaxonomyClass' => $custom_taxonomy_class_name,
                    ])->mountNewFile();
                }
            );
        } catch (FailedToCopyStub|PathNotFoundException $exception) {
            throw new FailedToCreateCustomTaxonomyDictionaryClassFile($exception);
        }

        return Command::SUCCESS;
    }
}
