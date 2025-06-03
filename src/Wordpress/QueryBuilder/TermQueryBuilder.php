<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder;

use Wordless\Application\Helpers\Arr;
use Wordless\Infrastructure\Wordpress\QueryBuilder;
use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\OrderBy;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\Resolver;

class TermQueryBuilder extends QueryBuilder
{
    use OrderBy;
    use Resolver;

    private const HIDE_EMPTY_KEY = 'hide_empty';
    private const OBJECT_IDS_KEY = 'object_ids';

    public function __construct(Taxonomy|string ...$taxonomies)
    {
        $this->doNotOrderBy()->evenWithoutAssociations();

        if (!empty($taxonomies)) {
            $this->onlyTaxonomies(...$taxonomies);
        }
    }

    public function evenWithoutAssociations(): static
    {
        $this->arguments[self::HIDE_EMPTY_KEY] = false;

        return $this;
    }

    public function except(Taxonomy|int $term, Taxonomy|int ...$terms): static
    {
        foreach (Arr::prepend($terms, $term) as $term) {
            if ($term instanceof Taxonomy) {
                $term = $term->term_id;
            }

            $this->arguments['exclude'][$term] = $term;
        }

        return $this;
    }

    public function exceptDescendents(Taxonomy|int $term, Taxonomy|int ...$terms): static
    {
        foreach (Arr::prepend($terms, $term) as $term) {
            if ($term instanceof Taxonomy) {
                $term = $term->term_id;
            }

            $this->arguments['exclude_tree'][$term] = $term;
        }

        return $this;
    }

    public function onlyAssociatedTo(IRelatedMetaData|int $object, IRelatedMetaData|int ...$objects): static
    {
        foreach (Arr::prepend($objects, $object) as $object) {
            if ($object instanceof IRelatedMetaData) {
                $object = $object->id();
            }

            if ($object > 0) {
                $this->arguments[self::OBJECT_IDS_KEY][$object] = $object;
            }
        }

        return $this;
    }

    public function onlyTaxonomies(Taxonomy|string $taxonomy, Taxonomy|string ...$taxonomies): static
    {
        foreach (Arr::prepend($taxonomies, $taxonomy) as $taxonomy) {
            if ($taxonomy instanceof Taxonomy) {
                $taxonomy = $taxonomy->taxonomy;
            }

            if (!empty($taxonomy)) {
                $this->arguments['taxonomy'][$taxonomy] = $taxonomy;
            }
        }

        return $this;
    }

    public function onlyWithAssociations(): static
    {
        $this->arguments[self::HIDE_EMPTY_KEY] = true;

        return $this;
    }
}
