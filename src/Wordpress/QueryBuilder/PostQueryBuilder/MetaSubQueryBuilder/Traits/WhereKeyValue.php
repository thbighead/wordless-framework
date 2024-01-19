<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\Between;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\EqualsTo;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\GreaterThan;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\GreaterThanOrEqualsTo;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\In;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\LessThan;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\LessThanOrEqualsTo;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\Like;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\NotBetween;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\NotEqualsTo;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\NotIn;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\NotLike;

trait WhereKeyValue
{
    use Between;
    use EqualsTo;
    use GreaterThan;
    use GreaterThanOrEqualsTo;
    use In;
    use LessThan;
    use LessThanOrEqualsTo;
    use Like;
    use NotBetween;
    use NotEqualsTo;
    use NotIn;
    use NotLike;
}
