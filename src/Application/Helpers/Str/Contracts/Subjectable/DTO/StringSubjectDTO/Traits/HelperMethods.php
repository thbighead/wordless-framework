<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits\Boolean;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits\Mutators;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits\Substring;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits\WordCase;
use Wordless\Application\Helpers\Str\Enums\Encoding;
use Wordless\Application\Helpers\Str\Enums\Language;

trait HelperMethods
{
    use Boolean;
    use Mutators;
    use Substring;
    use WordCase;

    public function length(?Encoding $encoding = null): int
    {
        return $this->length ?? $this->length = Str::length(
            $this->getOriginalSubject(),
            $this->resolveEncoding($encoding, func_get_args(), get_defined_vars())
        );
    }

    /**
     * @param Language|null $language
     * @return $this
     * @throws InvalidArgumentException
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
     * @throws InvalidArgumentException
     */
    public function singular(?Language $language = Language::english): static
    {
        $this->subject = Str::singular(
            $this->subject,
            $this->resolveLanguage($language, func_get_args(), get_defined_vars())
        );

        return $this->recalculateLength();
    }
}
