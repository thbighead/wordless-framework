<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Sidebar\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class SidebarRegistryNotFound extends DomainException
{
    public function __construct(readonly public string $sidebar_id, ?Throwable $previous = null)
    {
        parent::__construct(
            "Sidebar $this->sidebar_id not found in global \$wp_registered_sidebars entries.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
