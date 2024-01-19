<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Traits\WhereKeyValue\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait Exists
{
    public function whereKeyValueExists(string $key, string|int|float|bool|null $value = null): static
    {
        $this->arguments[] = $this->mountArgument($value, Type::char, $key, Compare::exists);

        return $this;
    }
}
