<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Http\Contracts;

use Wordless\Application\Helpers\Http\Contracts\Subjectable\DTO\HttpSubjectableDTO;
use Wordless\Application\Helpers\Http\Enums\Version;

abstract class Subjectable
{
    final public static function of(
        string $base_url,
        Version $version = Version::http_1_1,
        array   $default_headers = [],
        ?bool   $only_with_ssl = null
    ): HttpSubjectableDTO
    {
        return new HttpSubjectableDTO($base_url, $version, $default_headers, $only_with_ssl);
    }
}
