<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder\RecursiveSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\ArgumentMounter;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereValue;

class MetaSubQueryBuilder extends RecursiveSubQueryBuilder
{
    use ArgumentMounter;
    use WhereValue;

    final public const ARGUMENT_KEY = 'meta_query';
}
