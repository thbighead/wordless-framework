<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder\RecursiveSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\ArgumentMounter;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue;

class MetaSubQueryBuilder extends RecursiveSubQueryBuilder
{
    use ArgumentMounter;
    use WhereKeyValue;
    use WhereValue;

    final public const ARGUMENT_KEY = 'meta_query';
}
