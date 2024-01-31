<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Operator;

trait ArgumentMounter
{
    private const KEY_INCLUDE_CHILDREN = 'include_children';
    private const KEY_TAXONOMY = 'taxonomy';
    private const KEY_TERMS = 'terms';

    /**
     * @param Field $termField
     * @param string|int|string[]|int[] $term
     * @param string|null $taxonomy
     * @param Operator $operator
     * @param bool $include_children
     * @return array<string, string>
     */
    private function mountArgument(
        Field            $termField,
        string|int|array $term,
        ?string          $taxonomy = null,
        Operator         $operator = Operator::in,
        bool             $include_children = true
    ): array
    {
        $arguments = [
            Field::KEY => $termField->name,
            self::KEY_TERMS => $term,
            self::KEY_INCLUDE_CHILDREN => $include_children,
            Operator::KEY => $operator->value,
        ];

        if ($taxonomy !== null) {
            $arguments[self::KEY_TAXONOMY] = $taxonomy;
        }

        return $arguments;
    }
}
