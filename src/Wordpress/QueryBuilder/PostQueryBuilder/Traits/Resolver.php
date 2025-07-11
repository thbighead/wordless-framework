<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use stdClass;
use Wordless\Application\Helpers\Arr;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\PostStatus\Enums\StandardStatus;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\DateSubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Enums\PostsListFormat;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits\ArgumentsFixer;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver\Traits\Pagination;
use WP_Post;

trait Resolver
{
    use ArgumentsFixer;
    use Pagination;

    /**
     * @return int
     * @throws EmptyQueryBuilderArguments
     */
    public function count(): int
    {
        if (!$this->arePostsAlreadyLoaded()) {
            $this->getIds([self::KEY_NO_FOUND_ROWS => true]);
        }

        return $this->getQuery()->found_posts;
    }

    /**
     * @return bool
     * @throws EmptyQueryBuilderArguments
     */
    public function exists(): bool
    {
        return !empty($this->get());
    }

    /**
     * @param int $quantity
     * @return WP_Post|WP_Post[]|null
     * @throws EmptyQueryBuilderArguments
     */
    public function first(int $quantity = 1): WP_Post|array|null
    {
        return Arr::first($this->get(), $quantity);
    }

    /**
     * @param array $extra_arguments
     * @return array<int, WP_Post>
     * @throws EmptyQueryBuilderArguments
     */
    public function get(array $extra_arguments = []): array
    {
        $posts = [];

        foreach ($this->query($extra_arguments) as $post) {
            /** @var WP_Post $post */
            $posts[$post->ID] = $post;
        }

        return $posts;
    }

    /**
     * @param array $extra_arguments
     * @return int[]
     * @throws EmptyQueryBuilderArguments
     */
    public function getIds(array $extra_arguments = []): array
    {
        return $this->setPostsFormat(PostsListFormat::only_ids)
            ->query($extra_arguments);
    }

    /**
     * @return array<int, stdClass>
     * @throws EmptyQueryBuilderArguments
     */
    public function getParentsKeyedByChildId(): array
    {
        return $this->setPostsFormat(PostsListFormat::parents_keyed_by_child_ids)
            ->query();
    }

    /**
     * @param array $extra_arguments
     * @return array<string, string|int|bool|array>
     * @throws EmptyQueryBuilderArguments
     */
    protected function buildArguments(array $extra_arguments = []): array
    {
        $this->fixArguments();

        $arguments = parent::buildArguments();

        $this->resolveMimeTypeArgument($arguments)
            ->resolveStatusArgument($arguments)
            ->resolveDateSubQuery($arguments)
            ->resolveMetaSubQuery($arguments)
            ->resolveTaxonomySubQuery($arguments)
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

    /**
     * @param array $arguments
     * @return $this
     * @throws EmptyQueryBuilderArguments
     */
    private function resolveDateSubQuery(array &$arguments): static
    {
        $dateSubQueryBuilder = $arguments[DateSubQueryBuilder::ARGUMENT_KEY] ?? null;

        if ($dateSubQueryBuilder instanceof DateSubQueryBuilder) {
            $arguments[DateSubQueryBuilder::ARGUMENT_KEY] = $dateSubQueryBuilder->buildArguments();
        }

        return $this;
    }

    private function resolveExtraArguments(array &$arguments, array $extra_arguments): static
    {
        foreach ($extra_arguments as $extra_argument_key => $extra_argument_value) {
            $arguments[$extra_argument_key] = $extra_argument_value;
        }

        return $this;
    }

    private function resolveMimeTypeArgument(array &$arguments): static
    {
        if (!isset($arguments[self::KEY_ATTACHMENT_MIME_TYPE])) {
            return $this;
        }

        if (!$this->isWhereTypeIncludingAttachment()) {
            unset($arguments[self::KEY_ATTACHMENT_MIME_TYPE]);

            return $this;
        }

        $resolved_mime_type_argument = [];

        foreach ($arguments[self::KEY_ATTACHMENT_MIME_TYPE] as $mime_type_string => $mimeType) {
            $resolved_mime_type_argument[] = $mime_type_string;
        }

        $arguments[self::KEY_ATTACHMENT_MIME_TYPE] = $resolved_mime_type_argument;

        return $this;
    }

    private function resolveStatusArgument(array &$arguments): static
    {
        if (isset($arguments[self::KEY_POST_STATUS])) {
            $resolved_status_argument = [];

            foreach ($arguments[self::KEY_POST_STATUS] as $status_string) {
                if ($status_string instanceof StandardStatus) {
                    $resolved_status_argument[] = $status_string->value;

                    continue;
                }

                foreach (explode(',', $status_string) as $status) {
                    $resolved_status_argument[] = $status;
                }
            }

            $arguments[self::KEY_POST_STATUS] = $resolved_status_argument;
        }

        return $this;
    }

    /**
     * @param array $arguments
     * @return $this
     * @throws EmptyQueryBuilderArguments
     */
    private function resolveTaxonomySubQuery(array &$arguments): static
    {
        $taxonomySubQueryBuilder = $arguments[TaxonomySubQueryBuilder::ARGUMENT_KEY] ?? null;

        if ($taxonomySubQueryBuilder instanceof TaxonomySubQueryBuilder) {
            $arguments[TaxonomySubQueryBuilder::ARGUMENT_KEY] = $taxonomySubQueryBuilder->buildArguments();
        }

        return $this;
    }
}
