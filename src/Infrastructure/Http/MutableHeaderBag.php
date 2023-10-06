<?php

namespace Wordless\Infrastructure\Http;

interface MutableHeaderBag
{
    public function getHeader(string $key, string|array|null $default = null): string|array|null;

    /**
     * @return string[]
     */
    public function getHeaders(): array;

    public function hasHeader(string $key): bool;

    /**
     * @param string $key
     * @return $this
     */
    public function removeHeader(string $key): static;

    /**
     * @param string[] $headers
     * @return $this
     */
    public function removeHeaders(array $headers): static;

    /**
     * @param string $key
     * @param string $value
     * @param bool $override
     * @return $this
     */
    public function setHeader(string $key, string $value, bool $override = false): static;

    /**
     * @param string[] $headers
     * @param bool $override
     * @return $this
     */
    public function setHeaders(array $headers, bool $override = false): static;
}
