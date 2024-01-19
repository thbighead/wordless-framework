<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder\RecursiveSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\ArgumentMounter;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereValue;

class MetaSubQueryBuilder extends RecursiveSubQueryBuilder
{
    use ArgumentMounter;
    use WhereKeyValue;
    use WhereValue;

    final public const ARGUMENT_KEY = 'meta_query';
    private const NOT_EXISTS_BUG_VALUE = 'bug #23268';
}
