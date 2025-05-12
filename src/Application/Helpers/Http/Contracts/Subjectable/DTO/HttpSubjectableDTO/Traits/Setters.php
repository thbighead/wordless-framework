<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Http\Contracts\Subjectable\DTO\HttpSubjectableDTO\Traits;

use Wordless\Application\Helpers\Http\Enums\Version;
use Wordless\Application\Helpers\Str;

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

    public function setBaseUrl(string $new_base_url): self
    {
        $newBaseUrl = Str::of($new_base_url);

        while ($newBaseUrl->endsWith('/')) {
            $newBaseUrl->substring(-1);
        }

        $this->base_url = (string)$newBaseUrl;

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
