<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Plugin\Contracts\Subjectable\DTO\PluginSubjectDTO\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class PluginNotFound extends DomainException
{
    public function __construct(readonly public string $supposed_plugin, ?Throwable $previous = null)
    {
        parent::__construct(
            "No plugin installed named as '$this->supposed_plugin'.",
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
