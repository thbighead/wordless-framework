<?php

namespace Wordless\Wordpress\QueryBuilder;

use stdClass;
use Wordless\Application\Helpers\Arr;
use Wordless\Enums\QueryComparison;
use Wordless\Enums\QueryOrderByDirection;
use Wordless\Enums\WpQueryMeta;
use Wordless\Enums\WpQueryOrderByParameter;
use Wordless\Enums\WpQueryStatus;
use Wordless\Enums\WpQueryTaxonomy;
use Wordless\Infrastructure\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder;
use Wordless\Infrastructure\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder;
use Wordless\Infrastructure\Wordpress\QueryBuilder\WpQueryBuilder;
use Wordless\Wordpress\Models\Post;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\Pagination\Posts;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Enums\PostsListFormat;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PaginationArgumentsBuilder;
use WP_Post;
use WP_Query;

class PostQueryBuilder extends WpQueryBuilder
{
    final public const KEY_AUTHOR = 'author';
    final public const KEY_CATEGORY = 'cat';
    final public const KEY_HAS_PASSWORD = 'has_password';
    final public const KEY_IGNORE_STICKY_POSTS = 'ignore_sticky_posts';
    final public const KEY_NO_FOUND_ROWS = 'no_found_rows';
    final public const KEY_NO_PAGING = 'nopaging';
    final public const KEY_ORDER_BY = 'orderby';
    final public const KEY_POST_IN = 'post__in';
    final public const KEY_POST_NOT_IN = 'post__not_in';
    final public const KEY_POST_PASSWORD = 'post_password';
    final public const KEY_SEARCH = 's';

    /** @var array<string, bool> $search_words */
    private array $search_words = [];

    public static function getInstance(StandardType|PostType|null $post_type = null): static
    {
        return new static($post_type);
    }

    public function __construct(StandardType|PostType|null $postType = null)
    {
        $post_type = StandardType::ANY;

        if ($postType instanceof StandardType) {
            $post_type = $postType->name;
        }

        if ($postType instanceof PostType) {
            $post_type = $postType->name;
        }
        
        $this->whereType($post_type)
            ->withoutStickyPosts()
            ->deactivatePagination()
            ->setPostsFormat(PostsListFormat::all_fields);

        parent::__construct(new WP_Query);
    }

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

    public function getNumberOfPages(): ?int
    {
        if (!$this->arePostsAlreadyLoaded()) {
            $this->query();
        }

        return $this->getQuery()->max_num_pages;
    }

    /**
     * @return $this
     */
    public function onlyWithoutPassword(): PostQueryBuilder
    {
        $this->arguments[self::KEY_HAS_PASSWORD] = false;

        if (isset($this->arguments[self::KEY_POST_PASSWORD])) {
            unset($this->arguments[self::KEY_POST_PASSWORD]);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyWithPassword(?string $password): PostQueryBuilder
    {
        $this->arguments[self::KEY_HAS_PASSWORD] = true;

        if ($password !== null) {
            $this->arguments[self::KEY_POST_PASSWORD] = $password;
        }

        return $this;
    }

    /**
     * @param string|string[]|array<string,string> $columns use WpQueryOrderByParameter constants to avoid errors
     * @param string $direction ignored if $columns is an associative array
     * @return PostQueryBuilder
     */
    public function orderBy(
        string|array $columns,
        string       $direction = QueryOrderByDirection::ASCENDING
    ): PostQueryBuilder
    {
        if (!isset($this->arguments[self::KEY_ORDER_BY])) {
            $this->arguments[self::KEY_ORDER_BY] = [];
        }

        if (!is_array($columns)) {
            $columns = [$columns => $direction];
        }

        if (!Arr::isAssociative($columns)) {
            $order_by = [];

            foreach ($columns as $column) {
                $order_by[$column] = $direction;
            }

            $columns = $order_by;
            unset($order_by);
        }

        foreach ($columns as $column => $order_direction) {
            // ensuring the order of columns (if you ask it again it goes to the end of line)
            if (isset($this->arguments[self::KEY_ORDER_BY][$column])) {
                unset($this->arguments[self::KEY_ORDER_BY][$column]);
            }

            $this->arguments[self::KEY_ORDER_BY][$column] = $order_direction;
        }

        return $this;
    }

    /**
     * @param PaginationArgumentsBuilder $paginationBuilder
     * @return Posts
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public function paginate(PaginationArgumentsBuilder $paginationBuilder): Posts
    {
        return new Posts($this, $paginationBuilder);
    }


    /**
     * @param string|string[] $words
     * @param bool $sorted_by_relevance
     * @return $this
     */
    public function searchFor(string|array $words, bool $sorted_by_relevance = true): PostQueryBuilder
    {
        return $this->search($words, $sorted_by_relevance);
    }

    /**
     * @param string|string[] $words
     * @param bool $sorted_by_relevance
     * @return $this
     */
    public function searchNotFor(string|array $words, bool $sorted_by_relevance = true): PostQueryBuilder
    {
        return $this->search($words, $sorted_by_relevance, false);
    }

    /**
     * @param int|int[] $ids
     * @return PostQueryBuilder
     */
    public function whereAuthorId(int|array $ids): PostQueryBuilder
    {
        if (is_array($ids)) {
            $ids = implode(',', $ids);
        }

        $this->arguments[self::KEY_AUTHOR] = $ids;

        return $this;
    }

    /**
     * @param string $author_nice_name
     * @return $this
     */
    public function whereAuthorNiceName(string $author_nice_name): PostQueryBuilder
    {
        $this->arguments['author_name'] = $author_nice_name;

        return $this;
    }

    /**
     * @param int|int[] $ids
     * @param bool $and
     * @return $this
     */
    public function whereCategoryId(int|array $ids, bool $and = false): PostQueryBuilder
    {
        if (is_array($ids)) {
            $this->arguments[$and ? 'category__and' : 'category__in'] = $ids;

            return $this;
        }

        $this->arguments[self::KEY_CATEGORY] = $ids;

        return $this;
    }

    /**
     * @param string|string[] $names
     * @param bool $and
     * @return $this
     */
    public function whereCategoryName(string|array $names, bool $and = false): PostQueryBuilder
    {
        $this->arguments['category_name'] = is_array($names) ?
            implode($and ? '+' : ',', $names) : $names;

        return $this;
    }

    /**
     * @param int|int[] $post_ids
     * @return $this
     */
    public function whereId(int|array $post_ids): PostQueryBuilder
    {
        if (is_int($post_ids)) {
            $this->arguments['p'] = $post_ids;

            return $this;
        }

        if (empty($post_ids)) {
            return $this;
        }

        if (count($post_ids) === 1) {
            return $this->whereId($post_ids[0]);
        }

        $this->arguments[self::KEY_POST_IN] = $post_ids;

        if (isset($this->arguments[self::KEY_POST_NOT_IN])) {
            unset($this->arguments[self::KEY_POST_NOT_IN]);
        }

        return $this;
    }

    public function whereMeta(MetaSubQueryBuilder $subQuery): PostQueryBuilder
    {
        $this->arguments[WpQueryMeta::KEY_META_QUERY] = $subQuery;

        return $this;
    }

    /**
     * @param int|int[] $ids
     * @return PostQueryBuilder
     */
    public function whereNotAuthorId(int|array $ids): PostQueryBuilder
    {
        if (is_array($ids)) {
            $this->arguments['author__not_in'] = $ids;

            return $this;
        }

        $this->arguments[self::KEY_AUTHOR] = -$ids;

        return $this;
    }

    /**
     * @param int|int[] $ids
     * @return $this
     */
    public function whereNotCategoryId(int|array $ids): PostQueryBuilder
    {
        if (is_array($ids)) {
            $this->arguments['category__not_in'] = $ids;

            return $this;
        }

        $this->arguments[self::KEY_CATEGORY] = -$ids;

        return $this;
    }

    /**
     * @param int|int[] $post_ids
     * @return $this
     */
    public function whereNotId(int|array $post_ids): PostQueryBuilder
    {
        $post_ids = Arr::wrap($post_ids);

        if (empty($post_ids)) {
            return $this;
        }

        $this->arguments[self::KEY_POST_NOT_IN] = $post_ids;

        if (isset($this->arguments[self::KEY_POST_IN])) {
            unset($this->arguments[self::KEY_POST_IN]);
        }

        return $this;
    }

    /**
     * @param int[] $ids
     * @return $this
     */
    public function whereNotTagId(array $ids): PostQueryBuilder
    {
        $this->arguments['tag__not_in'] = $ids;

        return $this;
    }

    /**
     * @param string|string[] $slugs
     * @return $this
     */
    public function whereSlug(string|array $slugs): PostQueryBuilder
    {
        if (!is_array($slugs)) {
            $this->arguments['name'] = $slugs;

            return $this;
        }

        if (!empty($slugs)) {
            $this->arguments['post_name__in'] = $slugs;
        }

        return $this;
    }

    public function whereStatus(string $status): PostQueryBuilder
    {
        if ($this->isForTypeAttachment($this->arguments[PostType::QUERY_TYPE_KEY]) &&
            !($status === WpQueryStatus::ANY || $status === WpQueryStatus::INHERIT)) {
            $status = WpQueryStatus::INHERIT;
        }

        $this->arguments[WpQueryStatus::POST_STATUS_KEY] = $status;

        return $this;
    }

    /**
     * @param int|int[] $ids
     * @param bool $and
     * @return $this
     */
    public function whereTagId(int|array $ids, bool $and = false): PostQueryBuilder
    {
        if (is_array($ids)) {
            $this->arguments[$and ? 'tag__and' : 'tag__in'] = $ids;

            return $this;
        }

        $this->arguments['tag_id'] = $ids;

        return $this;
    }

    /**
     * @param string|string[] $names
     * @param bool $and
     * @return $this
     */
    public function whereTagName(string|array $names, bool $and = false): PostQueryBuilder
    {
        $this->arguments['tag'] = is_array($names) ?
            implode($and ? '+' : ',', $names) : $names;

        return $this;
    }

    public function whereTaxonomy(TaxonomySubQueryBuilder $subQuery): PostQueryBuilder
    {
        $this->arguments[WpQueryTaxonomy::KEY_TAXONOMY_QUERY] = $subQuery;

        return $this;
    }

    public function whereTitle(string $title): PostQueryBuilder
    {
        $this->arguments['title'] = $title;

        return $this;
    }

    /**
     * @param string|string[] $types
     * @return $this
     */
    public function whereType(string|array $types): PostQueryBuilder
    {
        if ($this->isForTypeAttachment($types) && !$this->isForStatusAny()) {
            $this->arguments[WpQueryStatus::POST_STATUS_KEY] = WpQueryStatus::INHERIT;
        }

        $this->arguments[PostType::QUERY_TYPE_KEY] = $types;

        return $this;
    }

    /**
     * @return $this
     */
    public function withAnyComments(): PostQueryBuilder
    {
        return $this->withComments();
    }

    /**
     * @param int $how_many
     * @param string $comparison use QueryComparisons constants to avoid errors
     * @return $this
     */
    public function withComments(
        int    $how_many = 1,
        string $comparison = QueryComparison::GREATER_THAN_OR_EQUAL
    ): PostQueryBuilder
    {
        $this->arguments['comment_count'] = ['compare' => $comparison, 'value' => $how_many];

        return $this;
    }

    /**
     * @param int $how_many
     * @return $this
     */
    public function withDifferentThanComments(int $how_many): PostQueryBuilder
    {
        return $this->withComments($how_many, QueryComparison::DIFFERENT);
    }

    /**
     * @param int $how_many
     * @return $this
     */
    public function withLessThanComments(int $how_many): PostQueryBuilder
    {
        return $this->withComments($how_many, QueryComparison::LESS_THAN);
    }

    /**
     * @param int $how_many
     * @return $this
     */
    public function withLessThanOrEqualsComments(int $how_many): PostQueryBuilder
    {
        return $this->withComments($how_many, QueryComparison::LESS_THAN_OR_EQUAL);
    }

    /**
     * @param int $how_many
     * @return $this
     */
    public function withMoreThanComments(int $how_many): PostQueryBuilder
    {
        return $this->withComments($how_many, QueryComparison::GREATER_THAN);
    }

    /**
     * @param int $how_many
     * @return $this
     */
    public function withMoreThanOrEqualsComments(int $how_many): PostQueryBuilder
    {
        return $this->withComments($how_many);
    }

    /**
     * @return $this
     */
    public function withoutComments(): PostQueryBuilder
    {
        return $this->withComments(0, QueryComparison::EQUAL);
    }

    /**
     * @return $this
     */
    public function withStickyPosts(): PostQueryBuilder
    {
        $this->arguments[self::KEY_IGNORE_STICKY_POSTS] = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function withoutStickyPosts(): PostQueryBuilder
    {
        $this->arguments[self::KEY_IGNORE_STICKY_POSTS] = true;

        return $this;
    }

    /**
     * @param array $extra_arguments
     * @return array<string, string|int|bool|array>
     */
    protected function buildArguments(array $extra_arguments = []): array
    {
        $arguments = $this->arguments;

        $metaSubQueryBuilder = $this->arguments[WpQueryMeta::KEY_META_QUERY] ?? null;
        if ($metaSubQueryBuilder instanceof MetaSubQueryBuilder) {
            $arguments[WpQueryMeta::KEY_META_QUERY] = $metaSubQueryBuilder->build();
        }

        $taxonomySubQueryBuilder = $this->arguments[WpQueryTaxonomy::KEY_TAXONOMY_QUERY] ?? null;
        if ($taxonomySubQueryBuilder instanceof TaxonomySubQueryBuilder) {
            $arguments[WpQueryTaxonomy::KEY_TAXONOMY_QUERY] = $taxonomySubQueryBuilder->build();
        }

        foreach ($extra_arguments as $extra_argument_key => $extra_argument_value) {
            $arguments[$extra_argument_key] = $extra_argument_value;
        }

        return $arguments;
    }

    /**
     * @return WP_Query
     */
    protected function getQuery(): WP_Query
    {
        return parent::getQuery();
    }

    private function arePostsAlreadyLoaded(): bool
    {
        return isset($this->getQuery()->posts);
    }

    private function deactivatePagination(): static
    {
        $this->arguments[self::KEY_NO_FOUND_ROWS] = true;
        $this->arguments[self::KEY_NO_PAGING] = true;
        $this->arguments[Posts::KEY_POSTS_PER_PAGE] = -1;

        return $this;
    }

    private function isForStatusAny(): bool
    {
        return ($this->arguments[WpQueryStatus::POST_STATUS_KEY] ?? null) === WpQueryStatus::ANY;
    }

    /**
     * @param string|string[] $types
     * @return bool
     */
    private function isForTypeAttachment(string|array $types): bool
    {
        return is_array($types) ?
            Arr::searchValueKey($types, StandardType::attachment->name) : $types === StandardType::attachment->name;
    }

    private function query(array $extra_arguments = []): array
    {
        $this->resolveSearch();

        return $this->getQuery()->query($this->buildArguments($extra_arguments));
    }

    private function resolveSearch(): void
    {
        foreach ($this->search_words as $word => $is_included) {
            $this->arguments[self::KEY_SEARCH] = isset($this->arguments[self::KEY_SEARCH]) ?
                "{$this->arguments[self::KEY_SEARCH]} " : '';

            $this->arguments[self::KEY_SEARCH] .= $is_included ? $word : "-$word";
        }
    }

    /**
     * @param string|string[] $words
     * @param bool $sorted_by_relevance
     * @param bool $include
     * @return $this
     */
    private function search(
        string|array $words,
        bool         $sorted_by_relevance = true,
        bool         $include = true
    ): PostQueryBuilder
    {
        if (empty($words)) {
            return $this;
        }

        foreach (Arr::wrap($words) as $word) {
            $this->search_words[$word] = $include;
        }

        if ($sorted_by_relevance) {
            $this->sortBySearchRelevance();
        }

        return $this;
    }

    private function setPostsFormat(PostsListFormat $format): static
    {
        $this->arguments[PostsListFormat::FIELDS_KEY] = $format->value;

        return $this;
    }

    private function sortBySearchRelevance(): void
    {
        $original_arguments = [];

        // ensuring the relevance will be the first parameter of ordering
        if (isset($this->arguments[self::KEY_ORDER_BY])) {
            $original_arguments = $this->arguments[self::KEY_ORDER_BY];
            unset($this->arguments[self::KEY_ORDER_BY]);
        }

        $this->orderBy(WpQueryOrderByParameter::SEARCH_RELEVANCE, QueryOrderByDirection::DESCENDING);

        // ensuring the relevance will be the first parameter of ordering
        $this->arguments[self::KEY_ORDER_BY] = $this->arguments[self::KEY_ORDER_BY] + $original_arguments;
    }
}
