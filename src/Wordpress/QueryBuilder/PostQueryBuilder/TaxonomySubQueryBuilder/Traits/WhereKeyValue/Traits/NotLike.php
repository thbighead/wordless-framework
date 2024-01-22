<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereKeyValue\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Type;

trait NotLike
{
    public function whereKeyValueNotLike(string $key, string $value): static
    {
        $this->arguments[] = $this->mountArgument($value, Type::char, $key, Compare::not_like);

        return $this;
    }
}
