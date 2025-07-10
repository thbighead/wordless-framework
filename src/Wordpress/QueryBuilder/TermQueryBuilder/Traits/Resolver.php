<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Enums\TermsListFormat;
use WP_Term;

trait Resolver
{
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

    private function resolveUniqueKeyedArgumentArray(array &$arguments, string $key): static
    {
        if (isset($arguments[$key])) {
            $arguments[$key] = array_keys($arguments[$key]);
        }

        return $this;
    }
}
