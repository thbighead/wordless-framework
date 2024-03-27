<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Role\Traits;

use Wordless\Wordpress\Models\Role\Traits\Repository\Traits\FromDatabase;
use Wordless\Wordpress\Models\Role\Traits\Repository\Traits\FromDictionary;

trait Repository
{
    use FromDatabase;
    use FromDictionary;
}
