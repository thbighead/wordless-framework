<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Http\Contracts\Subjectable\DTO\HttpSubjectableDTO\Traits;

use Wordless\Application\Helpers\Http\Enums\Version;

trait Setters
{
    public function setAsDefaultHeader(string $header, string $value): self
    {
        $this->default_headers[$header] = $value;

        return $this;
    }

    public function setAutomaticSslUsage(): self
    {
        $this->only_with_ssl = null;

        return $this;
    }

    public function setOnlyWithoutSsl(): self
    {
        $this->only_with_ssl = false;

        return $this;
    }

    public function setOnlyWithSsl(): self
    {
        $this->only_with_ssl = true;

        return $this;
    }

    public function setVersion(Version $version): self
    {
        $this->version = $version;

        return $this;
    }
}
