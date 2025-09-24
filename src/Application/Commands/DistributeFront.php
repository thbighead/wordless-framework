<?php declare(strict_types=1);

namespace Wordless\Application\Commands;

use Generator;
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\Exceptions\IOException;
use MatthiasMullie\Minify\JS;
use Symfony\Component\Console\Command\Command;
use Wordless\Application\Commands\DistributeFront\Enums\Type;
use Wordless\Application\Commands\DistributeFront\Exceptions\FailedToMountMinifiedAbsoluteFilepath;
use Wordless\Application\Commands\Exceptions\FailedToRunCommand;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\CannotReadPath;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToPutFileContent;
use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\ConsoleCommand;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\ArgumentDTO;
use Wordless\Infrastructure\ConsoleCommand\DTO\InputDTO\OptionDTO;

class DistributeFront extends ConsoleCommand
{
    final public const COMMAND_NAME = 'distribute';

    public function canRun(): bool
    {
        return Environment::isFramework();
    }

    /**
     * @return ArgumentDTO[]
     */
    protected function arguments(): array
    {
        return [];
    }

    protected function description(): string
    {
        return 'Create files into dist folder.';
    }


    protected function help(): string
    {
        return 'Minifies CSS and JS files from assets to dist folder.';
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
     * @throws FailedToRunCommand
     */
    protected function runIt(): int
    {
        try {
            foreach ($this->readCssAssetsDirectory() as $css_absolute_filepath) {
                $css_minified_absolute_filepath = $this->mountMinifiedAbsoluteFilepath($css_absolute_filepath);

                $this->wrapScriptWithMessages(
                    "Minifying $css_absolute_filepath to $css_minified_absolute_filepath...",
                    function () use ($css_absolute_filepath, $css_minified_absolute_filepath) {
                        $this->mountCssMinifier()
                            ->addFile($css_absolute_filepath)
                            ->minify($css_minified_absolute_filepath);
                    }
                );
            }

            foreach ($this->readJsAssetsDirectory() as $js_absolute_filepath) {
                $js_minified_absolute_filepath = $this->mountMinifiedAbsoluteFilepath($js_absolute_filepath);

                $this->wrapScriptWithMessages(
                    "Minifying $js_absolute_filepath to $js_minified_absolute_filepath...",
                    function () use ($js_absolute_filepath, $js_minified_absolute_filepath) {
                        $this->mountJsMinifier()
                            ->addFile($js_absolute_filepath)
                            ->minify($js_minified_absolute_filepath);
                    }
                );
            }

            return Command::SUCCESS;
        } catch (FailedToMountMinifiedAbsoluteFilepath|IOException|CannotReadPath $exception) {
            throw new FailedToRunCommand(static::COMMAND_NAME, $exception);
        }
    }

    /**
     * @param string $non_minified_filepath
     * @return string
     * @throws FailedToMountMinifiedAbsoluteFilepath
     */
    private function mountMinifiedAbsoluteFilepath(string $non_minified_filepath): string
    {
        $detectedFileType = Type::from(Str::afterLast($non_minified_filepath, '.'));

        try {
            return ProjectPath::dist((string)Str::of($non_minified_filepath)
                ->afterLast(DIRECTORY_SEPARATOR)
                ->before('.')
                ->wrap("$detectedFileType->name/", ".min.$detectedFileType->name"));
        } catch (PathNotFoundException $exception) {
            try {
                DirectoryFiles::createFileAt($exception->path);
            } catch (FailedToCreateDirectory|FailedToGetDirectoryPermissions|FailedToPutFileContent|PathNotFoundException $exception) {
                throw new FailedToMountMinifiedAbsoluteFilepath($exception);
            }

            return $exception->path;
        }
    }

    private function mountCssMinifier(): CSS
    {
        return new CSS;
    }

    private function mountJsMinifier(): JS
    {
        return new JS;
    }

    /**
     * @param Type $fileType
     * @return Generator|array
     * @throws CannotReadPath
     */
    private function readAssetsDirectoryOf(Type $fileType): Generator|array
    {
        try {
            $js_assets_absolute_path = ProjectPath::assets($fileType->name);
        } catch (PathNotFoundException) {
            return [];
        }

        return DirectoryFiles::recursiveRead($js_assets_absolute_path);
    }

    /**
     * @return Generator|array
     * @throws CannotReadPath
     */
    private function readCssAssetsDirectory(): Generator|array
    {
        return $this->readAssetsDirectoryOf(Type::css);
    }

    /**
     * @return Generator|array
     * @throws CannotReadPath
     */
    private function readJsAssetsDirectory(): Generator|array
    {
        return $this->readAssetsDirectoryOf(Type::js);
    }
}
