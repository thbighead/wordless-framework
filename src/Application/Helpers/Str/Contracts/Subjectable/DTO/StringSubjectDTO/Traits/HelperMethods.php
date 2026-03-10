<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Arr\Contracts\Subjectable\DTO\ArraySubjectDTO;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits\Boolean;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits\Mutators;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits\Substring;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits\WordCase;
use Wordless\Application\Helpers\Str\Enums\Encoding;
use Wordless\Application\Helpers\Str\Exceptions\JsonDecodeError;

/**
 * @mixin StringSubjectDTO
 */
trait HelperMethods
{
    use Boolean;
    use Mutators;
    use Substring;
    use WordCase;

    /**
     * @return ArraySubjectDTO
     * @throws JsonDecodeError
     */
    public function jsonDecode(): ArraySubjectDTO
    {
        return Arr::of(Str::jsonDecode($this->subject));
    }

    public function length(?Encoding $encoding = null): int
    {
        return $this->length ?? $this->length = Str::length(
            $this->getOriginalSubject(),
            $this->resolveEncoding($encoding, func_get_args(), get_defined_vars())
        );
    }
}
