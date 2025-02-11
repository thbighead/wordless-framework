<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Enums\Encoding;

trait WordCase
{
    /**
     * @return $this
     * @throws InvalidArgumentException
     */
    public function camelCase(): static
    {
        $this->subject = Str::camelCase($this->subject);

        return $this->recalculateLength();
    }

    /**
     * @return $this
     * @throws InvalidArgumentException
     */
    public function kebabCase(): static
    {
        $this->subject = Str::kebabCase($this->subject);

        return $this->recalculateLength();
    }

    public function lower(?Encoding $encoding = null): static
    {
        $this->subject = Str::lower(
            $this->subject,
            $this->resolveEncoding($encoding, func_get_args(), get_defined_vars())
        );

        return $this;
    }

    /**
     * @return $this
     * @throws InvalidArgumentException
     */
    public function pascalCase(): static
    {
        $this->subject = Str::pascalCase($this->subject);

        return $this->recalculateLength();
    }

    /**
     * @return $this
     * @throws InvalidArgumentException
     */
    public function slugCase(): static
    {
        $this->subject = Str::slugCase($this->subject);

        return $this->recalculateLength();
    }

    /**
     * @param string $delimiter
     * @param bool $upper_cased
     * @param Encoding|null $encoding
     * @return $this
     * @throws InvalidArgumentException
     */
    public function snakeCase(
        string    $delimiter = Str::UNDERSCORE,
        bool      $upper_cased = false,
        ?Encoding $encoding = null
    ): static
    {
        $this->subject = Str::snakeCase(
            $this->subject,
            $delimiter,
            $upper_cased,
            $this->resolveEncoding($encoding, func_get_args(), get_defined_vars())
        );

        return $this->recalculateLength();
    }

    /**
     * @return $this
     * @throws InvalidArgumentException
     */
    public function titleCase(?Encoding $encoding = Encoding::UTF_8): static
    {
        $this->subject = Str::titleCase(
            $this->subject,
            $this->resolveEncoding($encoding, func_get_args(), get_defined_vars())
        );

        return $this->recalculateLength();
    }

    public function upper(?Encoding $encoding = null): static
    {
        $this->subject = Str::upper(
            $this->subject,
            $this->resolveEncoding($encoding, func_get_args(), get_defined_vars())
        );

        return $this;
    }
}
