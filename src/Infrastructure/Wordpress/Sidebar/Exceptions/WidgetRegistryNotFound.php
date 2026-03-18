<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Sidebar\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class WidgetRegistryNotFound extends DomainException
{
    public function __construct(readonly public string $widget_id, ?Throwable $previous = null)
    {
        parent::__construct(
            "Widget $this->widget_id not found in global \$wp_registered_widgets entries.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
