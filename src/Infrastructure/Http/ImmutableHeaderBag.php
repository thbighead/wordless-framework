<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Http;

interface ImmutableHeaderBag
{
    public function getHeader(string $key, string|array|null $default = null): string|array|null;

    /**
     * @return string[]
     */
    public function getHeaders(): array;

    public function hasHeader(string $key): bool;
}
