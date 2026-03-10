<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits;

use RuntimeException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO;
use Wordless\Application\Helpers\Str\Enums\Encoding;
use Wordless\Application\Helpers\Str\Traits\Internal\Exceptions\FailedToCreateInflector;

/**
 * @mixin StringSubjectDTO
 */
trait WordCase
{
    /**
     * @return $this
     * @throws FailedToCreateInflector
     * @throws RuntimeException
     */
    public function camelCase(): static
    {
        $this->subject = Str::camelCase($this->subject);

        return $this->recalculateLength();
    }

    /**
     * @param bool $upper_cased
     * @return $this
     * @throws FailedToCreateInflector
     * @throws RuntimeException
     */
    public function kebabCase(bool $upper_cased = false): static
    {
        $this->subject = Str::kebabCase($this->subject, $upper_cased);

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
     * @throws FailedToCreateInflector
     * @throws RuntimeException
     */
    public function lowerKebabCase(): static
    {
        return $this->kebabCase();
    }

    /**
     * @return $this
     * @throws FailedToCreateInflector
     * @throws RuntimeException
     */
    public function lowerSlugCase(): static
    {
        return $this->slugCase();
    }

    /**
     * @param string $delimiter
     * @return $this
     * @throws FailedToCreateInflector
     * @throws RuntimeException
     */
    public function lowerSnakeCase(string $delimiter = Str::UNDERSCORE): static
    {
        return $this->snakeCase($delimiter);
    }

    /**
     * @return $this
     * @throws FailedToCreateInflector
     * @throws RuntimeException
     */
    public function pascalCase(): static
    {
        $this->subject = Str::pascalCase($this->subject);

        return $this->recalculateLength();
    }

    /**
     * @param bool $upper_cased
     * @return $this
     * @throws FailedToCreateInflector
     * @throws RuntimeException
     */
    public function slugCase(bool $upper_cased = false): static
    {
        $this->subject = Str::slugCase($this->subject, $upper_cased);

        return $this->recalculateLength();
    }

    /**
     * @param string $delimiter
     * @param bool $upper_cased
     * @param Encoding|null $encoding
     * @return $this
     * @throws FailedToCreateInflector
     * @throws RuntimeException
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
     * @param Encoding|null $encoding
     * @return $this
     * @throws FailedToCreateInflector
     * @throws RuntimeException
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

    /**
     * @return $this
     * @throws FailedToCreateInflector
     * @throws RuntimeException
     */
    public function upperKebabCase(): static
    {
        return $this->kebabCase(true);
    }

    /**
     * @return $this
     * @throws FailedToCreateInflector
     * @throws RuntimeException
     */
    public function upperSlugCase(): static
    {
        return $this->slugCase(true);
    }

    /**
     * @param string $delimiter
     * @return $this
     * @throws FailedToCreateInflector
     * @throws RuntimeException
     */
    public function upperSnakeCase(string $delimiter = Str::UNDERSCORE): static
    {
        return $this->snakeCase($delimiter, true);
    }
}
