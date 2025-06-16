<?php declare(strict_types=1);

namespace Wordless\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class FailedToRetrieveConfigFromWordpressConfigFile extends RuntimeException
{
    public function __construct(
        readonly public string  $ofKey,
        readonly public ?string $key = null,
        readonly public mixed   $default = null,
        ?Throwable              $previous = null
    )
    {
        parent::__construct(
            'Failed to retrieve config/wordpress.php.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
