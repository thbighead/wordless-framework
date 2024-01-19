<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereValue\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait NotExists
{
    public function whereValueNotExists(string|int|float|bool|null $value = null): static
    {
        $this->arguments[] = $this->mountArgument(
            $value ?? self::NOT_EXISTS_BUG_VALUE,
            Type::char,
            compare: Compare::not_exists
        );

        return $this;
    }
}
