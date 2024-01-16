<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use stdClass;
use Wordless\Enums\WpQueryTaxonomy;
use Wordless\Infrastructure\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder;
use Wordless\Infrastructure\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder;
use Wordless\Wordpress\Models\Post;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Enums\PostsListFormat;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Key;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits\ArgumentsFixer;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits\Pagination;
use WP_Post;

trait Resolver
{
    use ArgumentsFixer;
    use Pagination;

    public function count(): int
    {
        if (!$this->arePostsAlreadyLoaded()) {
            $this->getIds([self::KEY_NO_FOUND_ROWS => true]);
        }

        return $this->getQuery()->found_posts;
    }

    /**
     * @param bool $with_acfs
     * @return Post|null
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public function first(bool $with_acfs = false): ?Post
    {
        return $this->get($with_acfs)[0] ?? null;
    }

    /**
     * @param bool $with_acfs
     * @param array $extra_arguments
     * @return Post[]
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public function get(bool $with_acfs = false, array $extra_arguments = []): array
    {
        $posts = [];

        foreach ($this->query($extra_arguments) as $post) {
            /** @var WP_Post $post */
            $posts[$post->ID] = new Post($post, $with_acfs);
        }

        return $posts;
    }

    /**
     * @param array $extra_arguments
     * @return int[]
     */
    public function getIds(array $extra_arguments = []): array
    {
        return $this->setPostsFormat(PostsListFormat::only_ids)
            ->query($extra_arguments);
    }

    /**
     * @return array<int, stdClass>
     */
    public function getParentsKeyedByChildId(): array
    {
        return $this->setPostsFormat(PostsListFormat::parents_keyed_by_child_ids)
            ->query();
    }

    /**
     * @param array $extra_arguments
     * @return array<string, string|int|bool|array>
     */
    protected function buildArguments(array $extra_arguments = []): array
    {
        $this->fixArguments();

        $arguments = parent::buildArguments();

        $this->resolveStatusArgument($arguments)
            ->resolveMetaSubQuery($arguments)
            ->resolveTaxonomySubQuery($arguments)
            ->resolveExtraArguments($arguments, $extra_arguments);

        return $arguments;
    }

    private function query(array $extra_arguments = []): array
    {
        return $this->getQuery()->query($this->buildArguments($extra_arguments));
    }

    private function resolveExtraArguments(array &$arguments, array $extra_arguments): static
    {
        foreach ($extra_arguments as $extra_argument_key => $extra_argument_value) {
            $arguments[$extra_argument_key] = $extra_argument_value;
        }

        return $this;
    }

    private function resolveMetaSubQuery(array &$arguments): static
    {
        $metaSubQueryBuilder = $this->arguments[Key::key_meta_query->value] ?? null;

        if ($metaSubQueryBuilder instanceof MetaSubQueryBuilder) {
            $arguments[Key::key_meta_query->value] = $metaSubQueryBuilder->build();
        }

        return $this;
    }

    private function resolveStatusArgument(array &$arguments): static
    {
        if (isset($arguments[self::KEY_POST_STATUS])) {
            $arguments[self::KEY_POST_STATUS] = array_values($arguments[self::KEY_POST_STATUS]);
        }

        return $this;
    }

    private function resolveTaxonomySubQuery(array &$arguments): static
    {
        $taxonomySubQueryBuilder = $this->arguments[WpQueryTaxonomy::KEY_TAXONOMY_QUERY] ?? null;

        if ($taxonomySubQueryBuilder instanceof TaxonomySubQueryBuilder) {
            $arguments[WpQueryTaxonomy::KEY_TAXONOMY_QUERY] = $taxonomySubQueryBuilder->build();
        }

        return $this;
    }
}
