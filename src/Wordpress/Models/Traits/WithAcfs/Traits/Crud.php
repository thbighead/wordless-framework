<?php

namespace Wordless\Wordpress\Models\Traits\WithAcfs\Traits;

use Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Crud\Traits\CreateOrUpdate;
use Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Crud\Traits\Delete;
use Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Crud\Traits\Read;

trait Crud
{
    use CreateOrUpdate;
    use Delete;
    use Read;
}
