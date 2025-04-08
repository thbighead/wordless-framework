<?php declare(strict_types=1);

namespace Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory\FileWriter;

use Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory\FileWriter;
use Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory\FileWriter\Exceptions\FileWritingError;
use JsonException;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToPutFileContent;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

class JsonFileWriter extends FileWriter
{
    /**
     * @param array $translations
     * @return void
     * @throws FileWritingError
     */
    public function write(array $translations): void
    {
        try {
            DirectoryFiles::createFileAt($this->wp_translation_absolute_filepath, Arr::toJson($translations));
        } catch (JsonException|FailedToCreateDirectory|FailedToGetDirectoryPermissions|FailedToPutFileContent|PathNotFoundException $exception) {
            throw new FileWritingError($exception);
        }
    }
}
