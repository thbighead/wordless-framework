<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder;

use Wordless\Application\Helpers\Arr;
use Wordless\Infrastructure\Wordpress\QueryBuilder\WpQueryBuilder;
use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Enums\TermsListFormat;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Exceptions\DoNotUseNumberWithObjectIds;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\OrderBy;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\Resolver;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits\WhereClauses;
use Wordless\Wordpress\QueryBuilder\Traits\HasMetaSubQuery;
use WP_Term_Query;

class TermQueryBuilder extends WpQueryBuilder
{
    use HasMetaSubQuery;
    use OrderBy;
    use Resolver;
    use WhereClauses;

    private const EXCLUDE_KEY = 'exclude';
    private const EXCLUDE_TREE_KEY = 'exclude_tree';
    private const HIDE_EMPTY_KEY = 'hide_empty';
    private const NUMBER_KEY = 'number';
    private const OBJECT_IDS_KEY = 'object_ids';
    private const TAXONOMY_KEY = 'taxonomy';

    public static function make(Taxonomy|string ...$taxonomies): static
    {
        return new static(...$taxonomies);
    }

    public function __construct(Taxonomy|string ...$taxonomies)
    {
        parent::__construct();

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

            $this->arguments[self::EXCLUDE_KEY][$term] = $term;
        }

        return $this;
    }

    public function exceptDescendents(Taxonomy|int $term, Taxonomy|int ...$terms): static
    {
        foreach (Arr::prepend($terms, $term) as $term) {
            if ($term instanceof Taxonomy) {
                $term = $term->term_id;
            }

            $this->arguments[self::EXCLUDE_TREE_KEY][$term] = $term;
        }

        return $this;
    }

    public function haveNoChildren(): static
    {
        $this->arguments['childless'] = true;

        return $this;
    }

    /**
     * @param int $how_many
     * @return $this
     * @throws DoNotUseNumberWithObjectIds
     */
    public function limit(int $how_many): static
    {
        if (isset($this->arguments[self::OBJECT_IDS_KEY])) {
            throw new DoNotUseNumberWithObjectIds;
        }

        $this->arguments[self::NUMBER_KEY] = max(1, $how_many);

        return $this;
    }

    /**
     * @param IRelatedMetaData|int $object
     * @param IRelatedMetaData|int ...$objects
     * @return $this
     * @throws DoNotUseNumberWithObjectIds
     */
    public function onlyAssociatedTo(IRelatedMetaData|int $object, IRelatedMetaData|int ...$objects): static
    {
        if (isset($this->arguments[self::NUMBER_KEY])) {
            throw new DoNotUseNumberWithObjectIds;
        }

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

    public function onlyDescendentOf(int $ascendant_id): static
    {
        $this->arguments['child_of'] = $ascendant_id;

        return $this;
    }

    public function onlyTaxonomies(Taxonomy|string $taxonomy, Taxonomy|string ...$taxonomies): static
    {
        foreach (Arr::prepend($taxonomies, $taxonomy) as $taxonomy) {
            if ($taxonomy instanceof Taxonomy) {
                $taxonomy = $taxonomy->taxonomy;
            }

            if (!empty($taxonomy)) {
                $this->arguments[self::TAXONOMY_KEY][$taxonomy] = $taxonomy;
            }
        }

        return $this;
    }

    public function onlyWithAssociations(): static
    {
        $this->arguments[self::HIDE_EMPTY_KEY] = true;

        return $this;
    }

    /**
     * @return WP_Term_Query
     */
    protected function getQuery(): WP_Term_Query
    {
        return parent::getQuery();
    }

    protected function mountNewWpQuery(): WP_Term_Query
    {
        return new WP_Term_Query;
    }

    private function setTermsFormat(TermsListFormat $format): static
    {
        $this->arguments[TermsListFormat::FIELDS_KEY] = $format->value;

        return $this;
    }
}
