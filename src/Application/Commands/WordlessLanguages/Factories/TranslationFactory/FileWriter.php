<?php declare(strict_types=1);

namespace Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory;

abstract class FileWriter
{
    abstract public function write(array $translations): void;

    public function __construct(readonly protected string $wp_translation_absolute_filepath)
    {
    }
}
