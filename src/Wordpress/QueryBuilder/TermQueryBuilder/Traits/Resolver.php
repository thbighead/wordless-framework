<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\MetaSubQueryBuilder\Enums\Type;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Enums\TermsListFormat;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Exceptions\DoNotUseNumberWithObjectIds;
use WP_Term;

trait Resolver
{
    /**
     * @param int $quantity
     * @param array $extra_arguments
     * @return WP_Term|null
     * @throws DoNotUseNumberWithObjectIds
     * @throws EmptyQueryBuilderArguments
     */
    public function first(int $quantity = 1, array $extra_arguments = []): ?WP_Term
    {
        return Arr::first($this->limit($quantity = max(1, $quantity))
            ->get($extra_arguments), $quantity);
    }

    /**
     * @param int $quantity
     * @param array $extra_arguments
     * @return int|null
     * @throws DoNotUseNumberWithObjectIds
     * @throws EmptyQueryBuilderArguments
     */
    public function firstId(int $quantity = 1, array $extra_arguments = []): ?int
    {
        return Arr::first($this->limit($quantity = max(1, $quantity))
            ->getIds($extra_arguments), $quantity);
    }

    /**
     * @param int $quantity
     * @param array $extra_arguments
     * @return string|null
     * @throws DoNotUseNumberWithObjectIds
     * @throws EmptyQueryBuilderArguments
     */
    public function firstName(int $quantity = 1, array $extra_arguments = []): ?string
    {
        return Arr::first($this->limit($quantity = max(1, $quantity))
            ->getNames($extra_arguments), $quantity);
    }

    /**
     * @param int $quantity
     * @param array $extra_arguments
     * @return int|null
     * @throws DoNotUseNumberWithObjectIds
     * @throws EmptyQueryBuilderArguments
     */
    public function firstNumberOfAssociatedObjects(int $quantity = 1, array $extra_arguments = []): ?int
    {
        return Arr::first($this->limit($quantity = max(1, $quantity))
            ->getNumberOfAssociatedObjects($extra_arguments), $quantity);
    }

    /**
     * @param int $quantity
     * @param array $extra_arguments
     * @return int|null
     * @throws DoNotUseNumberWithObjectIds
     * @throws EmptyQueryBuilderArguments
     */
    public function firstParentId(int $quantity = 1, array $extra_arguments = []): ?int
    {
        return Arr::first($this->limit($quantity = max(1, $quantity))
            ->getParentIdsKeyedById($extra_arguments), $quantity);
    }

    /**
     * @param int $quantity
     * @param array $extra_arguments
     * @return string|null
     * @throws DoNotUseNumberWithObjectIds
     * @throws EmptyQueryBuilderArguments
     */
    public function firstSlug(int $quantity = 1, array $extra_arguments = []): ?string
    {
        return Arr::first($this->limit($quantity = max(1, $quantity))
            ->getSlugs($extra_arguments), $quantity);
    }

    /**
     * @param int $quantity
     * @param array $extra_arguments
     * @return string|null
     * @throws DoNotUseNumberWithObjectIds
     * @throws EmptyQueryBuilderArguments
     */
    public function firstTaxonomyTermId(int $quantity = 1, array $extra_arguments = []): ?string
    {
        return Arr::first($this->limit($quantity = max(1, $quantity))
            ->getTaxonomyTermIds($extra_arguments), $quantity);
    }

    /**
     * @return WP_Term[]
     * @throws EmptyQueryBuilderArguments
     */
    public function get(array $extra_arguments = []): array
    {
        $terms = [];

        if (isset($this->arguments[self::OBJECT_IDS_KEY]) && !isset($this->arguments[TermsListFormat::FIELDS_KEY])) {
            $this->arguments[TermsListFormat::FIELDS_KEY]
                = TermsListFormat::wp_terms_with_object_id_magic_property->value;
        }

        foreach ($this->query($extra_arguments) as $term) {
            /** @var WP_Term $term */
            $terms[$term->term_id] = $term;
        }

        return $terms;
    }

    /**
     * @param array $extra_arguments
     * @return int[]
     * @throws EmptyQueryBuilderArguments
     */
    public function getIds(array $extra_arguments = []): array
    {
        $this->arguments[TermsListFormat::FIELDS_KEY] = TermsListFormat::only_term_ids->value;

        return $this->query($extra_arguments);
    }

    /**
     * @param array $extra_arguments
     * @return string[]
     * @throws EmptyQueryBuilderArguments
     */
    public function getNames(array $extra_arguments = []): array
    {
        $this->arguments[TermsListFormat::FIELDS_KEY] = TermsListFormat::only_term_names->value;

        return $this->query($extra_arguments);
    }

    /**
     * @param array $extra_arguments
     * @return array<int, string>
     * @throws EmptyQueryBuilderArguments
     */
    public function getNamesKeyedById(array $extra_arguments = []): array
    {
        $this->arguments[TermsListFormat::FIELDS_KEY] = TermsListFormat::only_term_names_keyed_by_term_ids->value;

        return $this->query($extra_arguments);
    }

    /**
     * @param array $extra_arguments
     * @return int[]
     * @throws EmptyQueryBuilderArguments
     */
    public function getNumberOfAssociatedObjects(array $extra_arguments = []): array
    {
        $this->arguments[TermsListFormat::FIELDS_KEY] = TermsListFormat::number_of_associated_objects->value;

        return $this->query($extra_arguments);
    }

    /**
     * @param array $extra_arguments
     * @return array<int, int>
     * @throws EmptyQueryBuilderArguments
     */
    public function getParentIdsKeyedById(array $extra_arguments = []): array
    {
        $this->arguments[TermsListFormat::FIELDS_KEY] = TermsListFormat::only_parent_ids_keyed_by_term_ids->value;

        return $this->query($extra_arguments);
    }

    /**
     * @param array $extra_arguments
     * @return string[]
     * @throws EmptyQueryBuilderArguments
     */
    public function getSlugs(array $extra_arguments = []): array
    {
        $this->arguments[TermsListFormat::FIELDS_KEY] = TermsListFormat::only_term_slugs->value;

        return $this->query($extra_arguments);
    }

    /**
     * @param array $extra_arguments
     * @return array<int, string>
     * @throws EmptyQueryBuilderArguments
     */
    public function getSlugsKeyedById(array $extra_arguments = []): array
    {
        $this->arguments[TermsListFormat::FIELDS_KEY] = TermsListFormat::only_term_slugs_keyed_by_term_ids->value;

        return $this->query($extra_arguments);
    }

    /**
     * @param array $extra_arguments
     * @return int[]
     * @throws EmptyQueryBuilderArguments
     */
    public function getTaxonomyTermIds(array $extra_arguments = []): array
    {
        $this->arguments[TermsListFormat::FIELDS_KEY] = TermsListFormat::only_taxonomy_term_ids->value;

        return $this->query($extra_arguments);
    }

    /**
     * @param array $extra_arguments
     * @return array<string, string|int|bool|array>
     * @throws EmptyQueryBuilderArguments
     */
    protected function buildArguments(array $extra_arguments = []): array
    {
        $arguments = parent::buildArguments();

        $this->resolveExceptArguments($arguments)
            ->resolveOnlyAssociatedToArgument($arguments)
            ->resolveOnlyTaxonomiesArgument($arguments)
            ->resolveOrderByMeta($arguments)
            ->resolveMetaSubQuery($arguments)
            ->resolveExtraArguments($arguments, $extra_arguments);

        return $arguments;
    }

    /**
     * @param array $extra_arguments
     * @return array
     * @throws EmptyQueryBuilderArguments
     */
    private function query(array $extra_arguments = []): array
    {
        return $this->getQuery()->query($this->buildArguments($extra_arguments));
    }

    private function resolveExceptArguments(array &$arguments): static
    {
        return $this->resolveUniqueKeyedArgumentArray($arguments, self::EXCLUDE_KEY)
            ->resolveUniqueKeyedArgumentArray($arguments, self::EXCLUDE_TREE_KEY);
    }

    private function resolveExtraArguments(array &$arguments, array $extra_arguments): static
    {
        foreach ($extra_arguments as $extra_argument_key => $extra_argument_value) {
            $arguments[$extra_argument_key] = $extra_argument_value;
        }

        return $this;
    }

    private function resolveOnlyAssociatedToArgument(array &$arguments): static
    {
        return $this->resolveUniqueKeyedArgumentArray($arguments, self::OBJECT_IDS_KEY);
    }

    private function resolveOnlyTaxonomiesArgument(array &$arguments): static
    {
        return $this->resolveUniqueKeyedArgumentArray($arguments, self::TAXONOMY_KEY);
    }

    private function resolveOrderByMeta(array &$arguments): static
    {
        if (($meta_order_by_type_key = $arguments[self::META_ORDER_BY_TYPE_KEY] ?? null) instanceof Type) {
            $metaSubQuery = $arguments[MetaSubQueryBuilder::ARGUMENT_KEY] ?? MetaSubQueryBuilder::make();

            $metaSubQuery->hasKey($arguments[self::ORDER_BY_KEY], $meta_order_by_type_key);

            $arguments[MetaSubQueryBuilder::ARGUMENT_KEY] = $metaSubQuery;
        }

        return $this;
    }

    private function resolveUniqueKeyedArgumentArray(array &$arguments, string $key): static
    {
        if (isset($arguments[$key])) {
            $arguments[$key] = array_keys($arguments[$key]);
        }

        return $this;
    }
}
