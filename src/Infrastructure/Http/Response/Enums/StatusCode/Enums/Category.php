<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Http\Response\Enums\StatusCode\Enums;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;

enum Category: int
{
    case informational = 100;
    case success = 200;
    case redirection = 300;
    case client_error = 400;
    case server_error = 500;

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function asText(): string
    {
        return Str::titleCase($this->name);
    }
}
