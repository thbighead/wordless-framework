<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits;

use Wordless\Application\Helpers\Str;

trait Boolean
{
    public function beginsWith(string $substring): bool
    {
        return Str::beginsWith($this->subject, $substring);
    }

    /**
     * @param string|string[] $needles
     * @param bool $any
     * @return bool
     */
    public function contains(string|array $needles, bool $any = true): bool
    {
        return Str::contains($this->subject, $needles, $any);
    }

    public function endsWith(string $substring): bool
    {
        return Str::endsWith($this->subject, $substring);
    }

    public function isJson(): bool
    {
        return Str::isJson($this->subject);
    }

    public function isSurroundedBy(string $prefix, string $suffix): bool
    {
        return Str::isSurroundedBy($this->subject, $prefix, $suffix);
    }
}
