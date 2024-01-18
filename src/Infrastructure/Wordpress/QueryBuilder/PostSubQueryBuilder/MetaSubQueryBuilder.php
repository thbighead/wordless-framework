<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder;

abstract class MetaSubQueryBuilder extends PostSubQueryBuilder
{
    final public const ARGUMENT_KEY = 'meta_query';
}
