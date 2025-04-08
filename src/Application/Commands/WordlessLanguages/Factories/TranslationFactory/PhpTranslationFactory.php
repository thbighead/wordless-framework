<?php declare(strict_types=1);

namespace Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory;

use Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory;
use Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory\FileWriter\PhpFileWriter;
use Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory\JsonTranslationFactory\Exceptions\WpTranslationsReadingFail;

class PhpTranslationFactory extends TranslationFactory
{
    /**
     * @return array
     * @throws WpTranslationsReadingFail
     */
    public function readWpTranslations(): array
    {
        if (is_array($wp_translation = include $this->wp_translation_absolute_filepath)) {
            return $wp_translation;
        }

        throw new WpTranslationsReadingFail($this->wp_translation_absolute_filepath);
    }

    protected function mountFileWriter(): FileWriter
    {
        return new PhpFileWriter($this->wp_translation_absolute_filepath);
    }
}
