<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits;

use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Enums\Encoding;
use Wordless\Application\Helpers\Str\Enums\Language;

trait Internal
{
    private ?Encoding $encoding;
    private ?Language $language;
    private int $length;

    public function __construct(string $subject)
    {
        parent::__construct($subject);
    }

    public function __toString(): string
    {
        return $this->getSubject();
    }

    /**
     * @return string
     */
    public function getOriginalSubject(): string
    {
        return parent::getOriginalSubject();
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return parent::getSubject();
    }

    public function setEncoding(?Encoding $encoding): static
    {
        $this->encoding = $encoding;

        return $this;
    }

    public function setLanguage(?Language $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function unsetEncoding(): static
    {
        unset($this->encoding);

        return $this;
    }

    public function unsetLanguage(): static
    {
        unset($this->language);

        return $this;
    }

    private function recalculateLength(): static
    {
        if (is_int($this->length ?? false)) {
            $this->length = Str::length($this->subject);
        }

        return $this;
    }

    private function resolveEncoding(?Encoding $encoding, array $func_get_args, array $get_defined_vars): ?Encoding
    {
        return $this->resolveArgumentValueByProperty(
            'encoding',
            $encoding,
            'encoding',
            $func_get_args,
            $get_defined_vars
        );
    }

    private function resolveLanguage(?Language $language, array $func_get_args, array $get_defined_vars): ?Language
    {
        return $this->resolveArgumentValueByProperty(
            'language',
            $language,
            'language',
            $func_get_args,
            $get_defined_vars
        );
    }

    private function setLength(int $new_length): static
    {
        if (is_int($this->length ?? false)) {
            $this->length = abs($new_length);
        }

        return $this;
    }
}
