<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;

trait WordCase
{
    /**
     * @return $this
     * @throws InvalidArgumentException
     */
    public function camelCase(): static
    {
        $this->subject = Str::camelCase($this->subject);

        return $this;
    }

    /**
     * @return $this
     * @throws InvalidArgumentException
     */
    public function kebabCase(): static
    {
        $this->subject = Str::kebabCase($this->subject);

        return $this;
    }

    public function lower(): static
    {
        $this->subject = Str::lower($this->subject);

        return $this;
    }

    /**
     * @return $this
     * @throws InvalidArgumentException
     */
    public function pascalCase(): static
    {
        $this->subject = Str::pascalCase($this->subject);

        return $this;
    }

    /**
     * @return $this
     * @throws InvalidArgumentException
     */
    public function slugCase(): static
    {
        $this->subject = Str::slugCase($this->subject);

        return $this;
    }

    /**
     * @param string $delimiter
     * @return $this
     * @throws InvalidArgumentException
     */
    public function snakeCase(string $delimiter = '_'): static
    {
        $this->subject = Str::snakeCase($this->subject, $delimiter);

        return $this;
    }

    /**
     * @return $this
     * @throws InvalidArgumentException
     */
    public function titleCase(): static
    {
        $this->subject = Str::titleCase($this->subject);

        return $this;
    }

    public function upper(): static
    {
        $this->subject = Str::upper($this->subject);

        return $this;
    }
}
