<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereValue\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Type;

trait NotLike
{
    public function whereValueNotLike(string $value): static
    {
        $this->arguments[] = $this->mountArgument($value, Type::char, compare: Compare::not_like);

        return $this;
    }
}
