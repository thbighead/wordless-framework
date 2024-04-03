<?php declare(strict_types=1);

namespace Wordless\Infrastructure;

abstract class PackageProvider extends Provider
{
    public function registerConfig(): ?string
    {
        return null;
    }
}
