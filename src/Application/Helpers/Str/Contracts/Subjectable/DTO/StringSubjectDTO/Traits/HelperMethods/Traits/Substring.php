<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods\Traits;

use Wordless\Application\Helpers\Str;

trait Substring
{
    public function after(string $delimiter): static
    {
        $this->subject = Str::after($this->subject, $delimiter);

        return $this->recalculateLength();
    }

    public function afterLast(string $delimiter): static
    {
        $this->subject = Str::afterLast($this->subject, $delimiter);

        return $this->recalculateLength();
    }

    public function before(string $delimiter): static
    {
        $this->subject = Str::before($this->subject, $delimiter);

        return $this->recalculateLength();
    }

    public function beforeLast(string $delimiter): static
    {
        $this->subject = Str::beforeLast($this->subject, $delimiter);

        return $this->recalculateLength();
    }

    public function between(string $prefix, ?string $suffix = null): static
    {
        $this->subject = Str::between($this->subject, $prefix, $suffix);

        return $this->recalculateLength();
    }

    public function countSubstring(string $substring): int
    {
        return Str::countSubstring($this->subject, $substring);
    }

    public function limitWords(int $max_words = Str::DEFAULT_LIMIT_WORDS, string $limit_marker = '...'): static
    {
        $this->subject = Str::limitWords($this->subject, $max_words, $limit_marker);

        return $this->recalculateLength();
    }

    public function substring(int $offset, ?int $length = null): static
    {
        $this->subject = Str::substring($this->subject, $offset, $length);

        return $this->recalculateLength();
    }

    public function truncate(int $max_chars = Str::DEFAULT_TRUNCATE_SIZE): static
    {
        $this->subject = Str::truncate($this->subject, $max_chars);

        return $this->setLength($max_chars <= 0 ? Str::DEFAULT_TRUNCATE_SIZE : $max_chars);
    }
}
