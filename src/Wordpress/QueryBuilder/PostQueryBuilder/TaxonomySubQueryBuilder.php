<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\PostSubQueryBuilder\RecursiveSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Field;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Enums\Operator;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\ArgumentMounter;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereTermId;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereTermName;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereTermSlug;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder\Traits\WhereTermTaxonomyId;

class TaxonomySubQueryBuilder extends RecursiveSubQueryBuilder
{
    use ArgumentMounter;
    use WhereTermId;
    use WhereTermName;
    use WhereTermSlug;
    use WhereTermTaxonomyId;

    final public const ARGUMENT_KEY = 'tax_query';

    /**
     * @param Field $termField
     * @param string|int|string[]|int[] $term
     * @param string|null $taxonomy
     * @param Operator $operator
     * @return $this
     */
    private function where(
        Field            $termField,
        string|int|array $term,
        ?string          $taxonomy = null,
        Operator         $operator = Operator::in
    ): static
    {
        $this->arguments[] = $this->mountArgument($termField, $term, $taxonomy, $operator, false);

        return $this;
    }

    /**
     * @param Field $termField
     * @param string|int|string[]|int[] $term
     * @param string|null $taxonomy
     * @param Operator $operator
     * @return $this
     */
    private function whereIncludingChildren(
        Field            $termField,
        string|int|array $term,
        ?string          $taxonomy = null,
        Operator         $operator = Operator::in
    ): static
    {
        $this->arguments[] = $this->mountArgument($termField, $term, $taxonomy, $operator);

        return $this;
    }
}
