<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder\RecursiveSubQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder\RecursiveSubQueryBuilder;

abstract class TaxonomySubQueryBuilder extends RecursiveSubQueryBuilder
{
    final public const ARGUMENT_KEY = 'tax_query';
}
