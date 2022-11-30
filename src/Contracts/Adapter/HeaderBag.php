<?php

namespace Wordless\Contracts\Adapter;

interface HeaderBag
{
    public function getHeader(string $key, ?string $default = null): ?string;

    /**
     * @return string[]
     */
    public function getHeaders(): array;

    public function hasHeader(string $key): bool;

    /**
     * @param string $key
     * @return $this
     */
    public function removeHeader(string $key): self;

    /**
     * @param string[] $headers
     * @return $this
     */
    public function removeHeaders(array $headers): self;

    /**
     * @param string $key
     * @param string $value
     * @param bool $override
     * @return $this
     */
    public function setHeader(string $key, string $value, bool $override = false): self;

    /**
     * @param string[] $headers
     * @param bool $override
     * @return $this
     */
    public function setHeaders(array $headers, bool $override = false): self;
}
