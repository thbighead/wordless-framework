<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\User\Traits;

use Wordless\Wordpress\Models\User\Traits\Crud\Traits\Create;
use Wordless\Wordpress\Models\User\Traits\Crud\Traits\Delete;
use Wordless\Wordpress\Models\User\Traits\Crud\Traits\Read;
use Wordless\Wordpress\Models\User\Traits\Crud\Traits\Update;
use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\UserModelQueryBuilder;

trait Crud
{
    use Create;
    use Delete;
    use Read;
    use Update;

    public static function query(): UserModelQueryBuilder
    {
        return UserModelQueryBuilder::make();
    }
}
