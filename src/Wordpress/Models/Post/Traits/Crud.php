<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits;

use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\Delete;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\Read;

trait Crud
{
    use CreateAndUpdate;
    use Delete;
    use Read;
}
