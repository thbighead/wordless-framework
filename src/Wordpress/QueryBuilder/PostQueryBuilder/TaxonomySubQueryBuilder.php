<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder\RecursiveSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\ArgumentMounter;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereKeyValue;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue;

class TaxonomySubQueryBuilder extends RecursiveSubQueryBuilder
{
    use ArgumentMounter;
    use WhereKeyValue;
    use WhereValue;

    final public const ARGUMENT_KEY = 'meta_query';
}
