<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Component\Exceptions;

use Throwable;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

class TemplateNotFoundException extends PathNotFoundException
{
    public function __construct(string $path, ?Throwable $previous = null)
    {
        parent::__construct($path, $previous);
    }
}
