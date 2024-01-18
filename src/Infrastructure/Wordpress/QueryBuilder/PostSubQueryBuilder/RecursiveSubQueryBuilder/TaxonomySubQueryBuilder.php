<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder\RecursiveSubQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder;

abstract class TaxonomySubQueryBuilder extends PostSubQueryBuilder
{
    final public const ARGUMENT_KEY = 'tax_query';
}
