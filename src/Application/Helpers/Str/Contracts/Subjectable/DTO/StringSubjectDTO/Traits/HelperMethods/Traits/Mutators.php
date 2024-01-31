<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;

trait Mutators
{
    public function finishWith(string $finish_with): static
    {
        $this->subject = Str::finishWith($this->subject, $finish_with);

        return $this;
    }

    /**
     * @param string|string[] $search_to_remove
     * @return $this
     */
    public function remove(string|array $search_to_remove): static
    {
        $this->subject = Str::remove($this->subject, $search_to_remove);

        return $this;
    }

    public function removeSuffix(string $suffix): static
    {
        $this->subject = Str::removeSuffix($this->subject, $suffix);

        return $this;
    }

    /**
     * @param string|string[] $search
     * @param string|string[] $replace
     * @return $this
     */
    public function replace(string|array $search, string|array $replace): static
    {
        $this->subject = Str::replace($this->subject, $search, $replace);

        return $this;
    }

    public function startWith(string $start_with): static
    {
        $this->subject = Str::startWith($this->subject, $start_with);

        return $this;
    }

    /**
     * @return $this
     * @throws InvalidArgumentException
     */
    public function unnaccented(): static
    {
        $this->subject = Str::unaccented($this->subject);

        return $this;
    }
}
