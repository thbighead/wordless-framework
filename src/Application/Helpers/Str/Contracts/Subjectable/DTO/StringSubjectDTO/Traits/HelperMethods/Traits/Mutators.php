<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits;

use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO;
use Wordless\Application\Helpers\Str\Enums\Language;
use Wordless\Application\Helpers\Str\Traits\Internal\Exceptions\FailedToCreateInflector;

/**
 * @mixin StringSubjectDTO
 */
trait Mutators
{
    public function finishWith(string $finish_with): static
    {
        $this->subject = Str::finishWith($this->subject, $finish_with);

        return $this->recalculateLength();
    }

    /**
     * @param Language|null $language
     * @return $this
     * @throws FailedToCreateInflector
     */
    public function plural(?Language $language = Language::english): static
    {
        $this->subject = Str::plural(
            $this->subject,
            $this->resolveLanguage($language, func_get_args(), get_defined_vars())
        );

        return $this->recalculateLength();
    }

    /**
     * @param Language|null $language
     * @return $this
     * @throws FailedToCreateInflector
     */
    public function singular(?Language $language = Language::english): static
    {
        $this->subject = Str::singular(
            $this->subject,
            $this->resolveLanguage($language, func_get_args(), get_defined_vars())
        );

        return $this->recalculateLength();
    }

    /**
     * @param string|string[] $search_to_remove
     * @return $this
     */
    public function remove(string|array $search_to_remove): static
    {
        $this->subject = Str::remove($this->subject, $search_to_remove);

        return $this->recalculateLength();
    }

    public function removeSuffix(string $suffix): static
    {
        $this->subject = Str::removeSuffix($this->subject, $suffix);

        return $this->recalculateLength();
    }

    /**
     * @param string|string[] $search
     * @param string|string[] $replace
     * @return $this
     */
    public function replace(string|array $search, string|array $replace): static
    {
        $this->subject = Str::replace($this->subject, $search, $replace);

        return $this->recalculateLength();
    }

    public function startWith(string $start_with): static
    {
        $this->subject = Str::startWith($this->subject, $start_with);

        return $this->recalculateLength();
    }

    /**
     * @return $this
     * @throws FailedToCreateInflector
     */
    public function unaccented(): static
    {
        $this->subject = Str::unaccented($this->subject);

        return $this;
    }

    public function wrap(string $prefix = '/', ?string $suffix = null): static
    {
        $this->subject = Str::wrap($this->subject, $prefix, $suffix);

        return $this->recalculateLength();
    }
}
