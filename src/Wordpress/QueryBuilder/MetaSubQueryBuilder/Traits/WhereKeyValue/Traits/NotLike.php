<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits;

use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait NotLike
{
    public function whereKeyValueNotLike(string $key, string $value): static
    {
        $this->arguments[] = $this->mountArgument($value, Type::char, $key, Compare::not_like);

        return $this;
    }
}
