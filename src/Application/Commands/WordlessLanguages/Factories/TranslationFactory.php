<?php declare(strict_types=1);

namespace Wordless\Application\Commands\WordlessLanguages\Factories;

use Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory\FileWriter;

abstract class TranslationFactory
{
    abstract public function readWpTranslations(): array;

    abstract protected function mountFileWriter(): FileWriter;

    protected FileWriter $fileWriter;
    protected array $translations;

    public function __construct(readonly protected string $wp_translation_absolute_filepath)
    {
        $this->translations = $this->readWpTranslations();
        $this->fileWriter = $this->mountFileWriter();
    }

    public function addCustomTranslations(string $custom_absolute_filepath): static
    {
        $this->translations = array_replace_recursive($this->translations, include $custom_absolute_filepath);

        return $this;
    }

    public function rewrite(): void
    {
        $this->fileWriter->write($this->translations);
    }
}
