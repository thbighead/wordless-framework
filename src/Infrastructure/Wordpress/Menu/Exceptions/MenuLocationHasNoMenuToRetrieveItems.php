<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Menu\Exceptions;

use DomainException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Wordpress\Menu;

class MenuLocationHasNoMenuToRetrieveItems extends DomainException
{
    public function __construct(public readonly Menu $menuLocation, ?Throwable $previous = null)
    {
        parent::__construct(
            'Trying to get menu items from menu localized at '
            . $menuLocation::id()
            . '. No menu created at this location.',
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
