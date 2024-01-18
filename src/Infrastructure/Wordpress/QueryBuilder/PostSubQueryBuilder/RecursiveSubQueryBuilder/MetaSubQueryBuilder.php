<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder\RecursiveSubQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder\RecursiveSubQueryBuilder;

abstract class MetaSubQueryBuilder extends RecursiveSubQueryBuilder
{
    final public const ARGUMENT_KEY = 'meta_query';
    final protected const KEY_META_KEY = 'key';
    final protected const KEY_META_VALUE = 'value';
    final protected const KEY_META_VALUE_COMPARE = 'compare';
    final protected const KEY_META_VALUE_TYPE = 'type';
}
