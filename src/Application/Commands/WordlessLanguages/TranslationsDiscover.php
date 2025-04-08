<?php declare(strict_types=1);

namespace Wordless\Application\Commands\WordlessLanguages;

use Wordless\Application\Commands\WordlessLanguages\TranslationsDiscover\Exceptions\DiscoverFailed;
use Generator;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\InvalidDirectory;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO;

class TranslationsDiscover
{
    readonly private string $wp_languages_absolute_directory_path;
    readonly private string $language_absolute_directory_path;
    readonly private string $custom_relative_filepath_without_extension;
    private StringSubjectDTO $wpFilepath;

    /**
     * @param string $custom_absolute_filepath
     * @param string $language
     * @throws PathNotFoundException
     */
    public function __construct(readonly private string $custom_absolute_filepath, readonly private string $language)
    {
        $this->wp_languages_absolute_directory_path = ProjectPath::wpContent('languages');
        $this->language_absolute_directory_path = ProjectPath::root("languages/$language");
        $this->custom_relative_filepath_without_extension = Str::between(
            $this->custom_absolute_filepath,
            $this->language_absolute_directory_path,
            '.'
        );
    }

    /**
     * @return Generator<string>
     * @throws DiscoverFailed
     */
    public function discover(): Generator
    {
        try {
            foreach (DirectoryFiles::recursiveRead($this->wp_languages_absolute_directory_path) as $wp_absolute_filepath) {
                $this->wpFilepath = Str::of($wp_absolute_filepath);

                if (!$this->isValidFileExtension()) {
                    continue;
                }

                if ($this->calculateWpRelativeFilepath()->hasDiscovered()) {
                    yield $wp_absolute_filepath;
                }
            }
        } catch (InvalidDirectory|PathNotFoundException $exception) {
            throw new DiscoverFailed($exception);
        }
    }

    private function calculateWpRelativeFilepath(): static
    {
        $this->wpFilepath->after($this->wp_languages_absolute_directory_path);

        return $this;
    }

    private function hasDiscovered(): bool
    {
        return $this->wpFilepath
            ->beginsWith("$this->custom_relative_filepath_without_extension-$this->language");
    }

    private function isValidFileExtension(): bool
    {
        return $this->wpFilepath->endsWith('.php') || $this->wpFilepath->endsWith('.json');
    }
}
