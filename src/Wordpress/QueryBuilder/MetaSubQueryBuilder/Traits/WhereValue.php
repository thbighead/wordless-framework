<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits\Between;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits\EqualsTo;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits\Exists;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits\GreaterThan;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits\GreaterThanOrEqualsTo;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits\In;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits\LessThan;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits\LessThanOrEqualsTo;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits\Like;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits\NotBetween;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits\NotEqualsTo;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits\NotExists;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits\NotIn;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits\NotLike;

trait WhereValue
{
    use Between;
    use Exists;
    use EqualsTo;
    use GreaterThan;
    use GreaterThanOrEqualsTo;
    use In;
    use LessThan;
    use LessThanOrEqualsTo;
    use Like;
    use NotBetween;
    use NotEqualsTo;
    use NotExists;
    use NotIn;
    use NotLike;
}
