<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\Between;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\EqualsTo;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\Exists;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\GreaterThan;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\GreaterThanOrEqualsTo;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\In;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\LessThan;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\LessThanOrEqualsTo;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\Like;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\NotBetween;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\NotEqualsTo;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\NotExists;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\NotIn;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits\NotLike;

trait WhereKeyValue
{
    use Between;
    use EqualsTo;
    use Exists;
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
    use NotExists;
}
