<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits\Between;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits\EqualsTo;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits\Exists;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits\GreaterThan;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits\GreaterThanOrEqualsTo;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits\In;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits\LessThan;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits\LessThanOrEqualsTo;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits\Like;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits\NotBetween;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits\NotEqualsTo;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits\NotExists;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits\NotIn;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits\NotLike;

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
