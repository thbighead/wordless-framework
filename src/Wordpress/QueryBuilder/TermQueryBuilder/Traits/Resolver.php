<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Traits;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\QueryBuilder\TermQueryBuilder\Enums\TermsListFormat;
use WP_Term;

trait Resolver
{
    /**
     * @return WP_Term[]
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
     * @return array<string, string|int|bool|array>
     * @throws EmptyQueryBuilderArguments
     */
    protected function buildArguments(array $extra_arguments = []): array
    {
        $arguments = parent::buildArguments();

        $this->resolveExceptArguments($arguments)
            ->resolveOnlyAssociatedToArgument($arguments)
            ->resolveOnlyTaxonomiesArgument($arguments);

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
