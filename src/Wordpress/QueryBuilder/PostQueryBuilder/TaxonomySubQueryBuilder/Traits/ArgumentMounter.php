<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Type;

trait ArgumentMounter
{
    final protected const KEY_META_KEY = 'key';
    final protected const KEY_META_VALUE = 'value';
    final protected const KEY_META_VALUE_COMPARE = 'compare';
    final protected const KEY_META_VALUE_TYPE = 'type';

    /**
     * @param string|int|float|bool|array<int, string|int|float|bool>|null $value
     * @param Type $valueType
     * @param string|null $key
     * @param Compare $compare
     * @return array
     */
    private function mountArgument(
        string|int|float|bool|array|null $value,
        Type $valueType,
        ?string $key = null,
        Compare $compare = Compare::equals
    ): array
    {
        $argument = [
            self::KEY_META_VALUE_COMPARE => $compare->value,
            self::KEY_META_VALUE_TYPE => $valueType->value,
        ];

        if ($value !== null) {
            $argument[self::KEY_META_VALUE] = $value;
        }

        if ($key !== null) {
            $argument[self::KEY_META_KEY] = $key;
        }

        return $argument;
    }
}
