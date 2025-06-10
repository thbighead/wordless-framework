<?php declare(strict_types=1);

namespace Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory;

use JsonException;
use Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory;
use Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory\FileWriter\JsonFileWriter;
use Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory\JsonTranslationFactory\Exceptions\WpTranslationsReadingFail;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetFileContent;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;

class JsonTranslationFactory extends TranslationFactory
{
    /**
     * @return array
     * @throws JsonException
     * @throws WpTranslationsReadingFail
     */
    public function readWpTranslations(): array
    {
        try {
            return Str::jsonDecode(DirectoryFiles::getFileContent($this->wp_translation_absolute_filepath));
        } catch (FailedToGetFileContent|PathNotFoundException $exception) {
            throw new WpTranslationsReadingFail(previous: $exception);
        }
    }

    protected function mountFileWriter(): FileWriter
    {
        return new JsonFileWriter($this->wp_translation_absolute_filepath);
    }
}
