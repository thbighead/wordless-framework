<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Guesser;

use Wordless\Infrastructure\Guesser;

abstract class ControllerGuesser extends Guesser
{
    protected string $controller_namespace_class;

    public function __construct(string $controller_namespace_class)
    {
        $this->controller_namespace_class = $controller_namespace_class;
    }
}
