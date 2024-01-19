<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait Exists
{
    public function whereValueExists(string|int|float|bool|null $value = null): static
    {
        $this->arguments[] = $this->mountArgument($value, Type::char, compare: Compare::exists);

        return $this;
    }
}
