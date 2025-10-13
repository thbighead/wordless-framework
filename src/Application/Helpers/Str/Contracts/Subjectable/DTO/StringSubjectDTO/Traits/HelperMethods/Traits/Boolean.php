<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits;

use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Enums\Encoding;

trait Boolean
{
    public function beginsWith(string $substring): bool
    {
        return Str::beginsWith($this->subject, $substring);
    }

    /**
     * @param string|string[] $needles
     * @param bool $any
     * @param Encoding|null $encoding
     * @return bool
     */
    public function contains(string|array $needles, bool $any = true, ?Encoding $encoding = null): bool
    {
        return Str::contains(
            $this->subject,
            $needles,
            $any,
            $this->resolveEncoding($encoding, func_get_args(), get_defined_vars())
        );
    }

    public function endsWith(string $substring): bool
    {
        return Str::endsWith($this->subject, $substring);
    }

    public function isEmpty(): bool
    {
        return Str::isEmpty($this->subject);
    }

    public function isJson(): bool
    {
        return Str::isJson($this->subject);
    }

    public function isSurroundedBy(string $prefix, string $suffix): bool
    {
        return Str::isWrappedBy($this->subject, $prefix, $suffix);
    }
}
