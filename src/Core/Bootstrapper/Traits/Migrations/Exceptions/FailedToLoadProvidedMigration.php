<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Provider;

class FailedToLoadProvidedMigration extends RuntimeException
{
    public function __construct(
        readonly public Provider $provider,
        readonly public string   $migration_absolute_path,
        ?Throwable               $previous = null
    )
    {
        parent::__construct(
            "Failed to load migration at '$this->migration_absolute_path'. Provided from "
            . $this->provider::class,
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
