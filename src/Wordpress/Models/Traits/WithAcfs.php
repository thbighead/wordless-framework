<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Traits;

use Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Crud;
use Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Loader;
use Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Validate;

trait WithAcfs
{
    use Crud;
    use Loader;
    use Validate;

    abstract protected function mountFromId(): string|int;
}
