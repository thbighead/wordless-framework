<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Http\Contracts\Subjectable\DTO;

use Wordless\Application\Helpers\Http\Contracts\Subjectable\DTO\HttpSubjectableDTO\Traits\Requests;
use Wordless\Application\Helpers\Http\Contracts\Subjectable\DTO\HttpSubjectableDTO\Traits\Setters;
use Wordless\Application\Helpers\Http\Enums\Version;

final class HttpSubjectableDTO
{
    use Requests;
    use Setters;

    /**
     * @param Version $version
     * @param array<string, string> $default_headers
     * @param bool|null $only_with_ssl
     * @noinspection PhpPropertyCanBeReadonlyInspection
     */
    public function __construct(
        private Version $version = Version::http_1_1,
        private array   $default_headers = [],
        private ?bool   $only_with_ssl = null
    )
    {
    }
}
