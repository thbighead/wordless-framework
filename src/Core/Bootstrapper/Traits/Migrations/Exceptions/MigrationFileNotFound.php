<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits\Migrations\Exceptions;

use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

class MigrationFileNotFound extends PathNotFoundException
{
}
