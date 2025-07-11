<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Compare;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Type;

trait ArgumentMounter
{
    private const KEY_META_KEY = 'key';
    private const KEY_META_VALUE = 'value';

    /**
     * @param string|int|float|bool|array<int, string|int|float|bool>|null $value
     * @param Type $valueType
     * @param string|null $key
     * @param Compare $compare
     * @return array
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function mountArgument(
        string|int|float|bool|array|null $value,
        Type                             $valueType,
        ?string                          $key = null,
        Compare                          $compare = Compare::equals
    ): array
    {
        $argument = [
            Compare::KEY => $compare->value,
            Type::KEY => $valueType->value,
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
