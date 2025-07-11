<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits;

use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait NotLike
{
    public function whereValueNotLike(string $value): static
    {
        $this->arguments[] = $this->mountArgument($value, Type::char, compare: Compare::not_like);

        return $this;
    }
}
