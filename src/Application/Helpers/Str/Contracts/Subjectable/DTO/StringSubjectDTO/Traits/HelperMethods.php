<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits;

use Doctrine\Inflector\Language;
use InvalidArgumentException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits\Boolean;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits\Mutators;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits\Substring;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits\WordCase;
use Wordless\Application\Helpers\Str\Enums\Encoding;

trait HelperMethods
{
    use Boolean;
    use Mutators;
    use Substring;
    use WordCase;

    public function length(?Encoding $encoding = null): int
    {
        return Str::length($this->getOriginalSubject(), $encoding);
    }

    /**
     * @param string $language
     * @return $this
     * @throws InvalidArgumentException
     */
    public function plural(string $language = Language::ENGLISH): static
    {
        $this->subject = Str::plural($this->subject, $language);

        return $this;
    }

    /**
     * @param string $language
     * @return $this
     * @throws InvalidArgumentException
     */
    public function singular(string $language = Language::ENGLISH): static
    {
        $this->subject = Str::singular($this->subject, $language);

        return $this;
    }
}
