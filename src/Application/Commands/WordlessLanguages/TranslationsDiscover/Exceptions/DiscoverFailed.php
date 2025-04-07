<?php declare(strict_types=1);

namespace Wordless\Application\Commands\WordlessLanguages\TranslationsDiscover\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class DiscoverFailed extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Failed to discover translations.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
