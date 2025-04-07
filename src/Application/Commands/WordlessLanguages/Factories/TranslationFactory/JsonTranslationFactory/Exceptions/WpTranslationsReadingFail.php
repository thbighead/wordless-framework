<?php declare(strict_types=1);

namespace Wordless\Application\Commands\WordlessLanguages\Factories\TranslationFactory\JsonTranslationFactory\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class WpTranslationsReadingFail extends RuntimeException
{
    public function __construct(readonly public ?string $wp_php_absolute_filepath = null, ?Throwable $previous = null)
    {
        parent::__construct($this->mountMessage(), ExceptionCode::intentional_interrupt->value, $previous);
    }

    private function mountMessage(): string
    {
        return $this->wp_php_absolute_filepath === null
            ? 'Couldn\'t read a file in wp-content/languages.'
            : "Couldn't resolve an array from $this->wp_php_absolute_filepath.";
    }
}
