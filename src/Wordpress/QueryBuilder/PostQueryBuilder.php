<?php

namespace Wordless\Wordpress\QueryBuilder;

use Wordless\Application\Helpers\Arr;
use Wordless\Enums\QueryComparison;
use Wordless\Enums\QueryOrderByDirection;
use Wordless\Enums\WpQueryFields;
use Wordless\Enums\WpQueryMeta;
use Wordless\Enums\WpQueryOrderByParameter;
use Wordless\Enums\WpQueryStatus;
use Wordless\Enums\WpQueryTaxonomy;
use Wordless\Infrastructure\Wordpress\QueryBuilder;
use Wordless\Infrastructure\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder;
use Wordless\Infrastructure\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder;
use Wordless\Wordpress\Models\Post;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\Pagination\Posts;
use WP_Post;
use WP_Query;

class PostQueryBuilder extends QueryBuilder
{
    public const KEY_AUTHOR = 'author';
    public const KEY_CATEGORY = 'cat';
    public const KEY_HAS_PASSWORD = 'has_password';
    public const KEY_IGNORE_STICKY_POSTS = 'ignore_sticky_posts';
    public const KEY_ORDER_BY = 'orderby';
    public const KEY_POST_IN = 'post__in';
    public const KEY_POST_NOT_IN = 'post__not_in';
    public const KEY_POST_PASSWORD = 'post_password';
    public const KEY_SEARCH = 's';

    private bool $load_acfs = false;
    /** @var array<string, bool> $search_words */
    private array $search_words = [];

    public function __construct(StandardType|PostType|null $post_type = null)
    {
        $this->whereType($post_type)
            ->withoutStickyPosts();
        $this->arguments[WpQueryFields::FIELDS_KEY] = WpQueryFields::LIST_OF_POSTS;

        parent::__construct(new WP_Query);
    }

    public function count(): int
    {
        if (!$this->arePostsAlreadyLoaded()) {
            $this->query();
        }

        return $this->getQuery()->found_posts;
    }

    /**
     * @param bool $with_acfs
     * @return Post[]
     */
    public function get(bool $with_acfs = false): array
    {
        $this->load_acfs = $with_acfs;
        $posts = [];

        foreach ($this->query() as $post) {
            /** @var WP_Post $post */
            $posts[$post->ID] = new Post($post, $with_acfs);
        }

        return $posts;
    }

    /**
     * @return int[]
     */
    public function getIds(): array
    {
        $this->load_acfs = false;
        $this->arguments[WpQueryFields::FIELDS_KEY] = WpQueryFields::LIST_OF_POSTS_IDS;

        return $this->query();
    }

    public function isPaginated(): bool
    {
        return isset($this->arguments[Posts::KEY_POSTS_PER_PAGE]);
    }

    public function getNumberOfPages(): ?int
    {
        if (!$this->isPaginated()) {
            return null;
        }

        if (!$this->arePostsAlreadyLoaded()) {
            $this->query();
        }

        return $this->getQuery()->max_num_pages;
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
     * @param string|string[]|array<string,string> $columns use WpQueryOrderByParameter constants to avoid errors
     * @param string $direction ignored if $columns is an associative array
     * @return PostQueryBuilder
     */
    public function orderBy(
        array|string $columns,
        string $direction = QueryOrderByDirection::ASCENDING
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

    public function paginate(
        int  $page = Posts::FIRST_PAGE,
        int  $posts_per_page = Posts::DEFAULT_POSTS_PER_PAGE,
        bool $with_acfs = false
    ): Posts
    {
        $this->load_acfs = $with_acfs;
        $this->setPostsPerPage($posts_per_page);

        return new Posts($this, $this->setPaged($page));
    }

    /**
     * @param int $page
     * @return $this
     */
    public function pagedAt(int $page): PostQueryBuilder
    {
        $this->setPaged($page);

        return $this;
    }

    /**
     * @param string|string[] $words
     * @param bool $sorted_by_relevance
     * @return $this
     */
    public function searchFor(array|string $words, bool $sorted_by_relevance = true): PostQueryBuilder
    {
        return $this->search($words, $sorted_by_relevance);
    }

    /**
     * @param string|string[] $words
     * @param bool $sorted_by_relevance
     * @return $this
     */
    public function searchNotFor(array|string $words, bool $sorted_by_relevance = true): PostQueryBuilder
    {
        return $this->search($words, $sorted_by_relevance, false);
    }

    /**
     * @param int $posts_per_page
     * @return $this
     */
    public function setPostsPerPage(int $posts_per_page = Posts::DEFAULT_POSTS_PER_PAGE): PostQueryBuilder
    {
        $this->arguments[Posts::KEY_POSTS_PER_PAGE] = $posts_per_page;

        return $this;
    }

    public function shouldLoadAcfs(): bool
    {
        return $this->load_acfs;
    }

    /**
     * @param int|int[] $ids
     * @return PostQueryBuilder
     */
    public function whereAuthorId(array|int $ids): PostQueryBuilder
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
    public function whereCategoryId(array|int $ids, bool $and = false): PostQueryBuilder
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
    public function whereCategoryName(array|string $names, bool $and = false): PostQueryBuilder
    {
        $this->arguments['category_name'] = is_array($names) ?
            implode($and ? '+' : ',', $names) : $names;

        return $this;
    }

    /**
     * @param int|int[] $post_ids
     * @return $this
     */
    public function whereId(array|int $post_ids): PostQueryBuilder
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
    public function whereNotAuthorId(array|int $ids): PostQueryBuilder
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
    public function whereNotCategoryId(array|int $ids): PostQueryBuilder
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
    public function whereNotId(array|int $post_ids): PostQueryBuilder
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
    public function whereSlug(array|string $slugs): PostQueryBuilder
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
    public function whereTagId(array|int $ids, bool $and = false): PostQueryBuilder
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
    public function whereTagName(array|string $names, bool $and = false): PostQueryBuilder
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
    public function whereType(array|string $types): PostQueryBuilder
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
        return $this->withComments($how_many, QueryComparison::GREATER_THAN_OR_EQUAL);
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
     * @return array<string, string|int|bool|array>
     */
    protected function buildArguments(): array
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

    private function isForStatusAny(): bool
    {
        return ($this->arguments[WpQueryStatus::POST_STATUS_KEY] ?? null) === WpQueryStatus::ANY;
    }

    /**
     * @param string|string[] $types
     * @return bool
     */
    private function isForTypeAttachment(array|string $types): bool
    {
        return is_array($types) ?
            Arr::searchValueKey($types, PostType::ATTACHMENT) : $types === PostType::ATTACHMENT;
    }

    private function query(): array
    {
        $this->resolveSearch();

        return parent::get();
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
        array|string $words,
        bool $sorted_by_relevance = true,
        bool $include = true
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

    private function setPaged(int $page)
    {
        return $this->arguments[Posts::KEY_PAGED] = max($page, Posts::FIRST_PAGE);
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
