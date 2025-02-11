<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits;

use Wordless\Application\Helpers\Str\Enums\Language;

trait Internal
{
    private ?Language $language;

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

    public function setLanguage(?Language $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function unsetLanguage(): static
    {
        unset($this->language);

        return $this;
    }

    private function resolveLanguage(?Language $language, array $func_get_args): ?Language
    {
        if (isset($this->language) && empty($func_get_args)) {
            return $this->language;
        }

        return $language;
    }
}
