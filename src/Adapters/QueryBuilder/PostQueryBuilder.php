<?php

namespace Wordless\Adapters\QueryBuilder;

use Wordless\Abstractions\Enums\WpQueryFields;
use Wordless\Abstractions\Enums\WpQueryStatus;
use Wordless\Abstractions\Enums\WpQueryTaxonomy;
use Wordless\Abstractions\Pagination\Posts;
use Wordless\Adapters\Post;
use Wordless\Adapters\PostType;
use Wordless\Adapters\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder;
use Wordless\Exceptions\QueryAlreadySet;
use Wordless\Helpers\Arr;
use Wordless\Helpers\Log;
use WP_Post;
use WP_Query;

class PostQueryBuilder extends QueryBuilder
{
    public const KEY_AUTHOR = 'author';
    public const KEY_CATEGORY = 'cat';
    public const KEY_HAS_PASSWORD = 'has_password';
    public const KEY_POST_PASSWORD = 'post_password';
    private bool $load_acfs = false;

    public function __construct(string $post_type = PostType::POST)
    {
        try {
            $this->setQuery(new WP_Query)
                ->whereType($post_type);
            $this->arguments[WpQueryFields::FIELDS_KEY] = WpQueryFields::LIST_OF_POSTS;

            parent::__construct(WP_Query::class);
        } catch (QueryAlreadySet $exception) {
            Log::error("This is impossible, but... {$exception->getMessage()}");
        }
    }

    public function count(): int
    {
        if (!$this->arePostsAlreadyLoaded()) {
            parent::get();
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

        foreach (parent::get() as $post) {
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

        return parent::get();
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
            parent::get();
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
    public function whereAuthorId($ids): PostQueryBuilder
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
    public function whereCategoryId($ids, bool $and = false): PostQueryBuilder
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
    public function whereCategoryName($names, bool $and = false): PostQueryBuilder
    {
        $this->arguments['category_name'] = is_array($names) ?
            implode($and ? '+' : ',', $names) : $names;

        return $this;
    }

    /**
     * @param int|int[] $ids
     * @return PostQueryBuilder
     */
    public function whereNotAuthorId($ids): PostQueryBuilder
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
    public function whereNotCategoryId($ids): PostQueryBuilder
    {
        if (is_array($ids)) {
            $this->arguments['category__not_in'] = $ids;

            return $this;
        }

        $this->arguments[self::KEY_CATEGORY] = -$ids;

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
    public function whereTagId($ids, bool $and = false): PostQueryBuilder
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
    public function whereTagName($names, bool $and = false): PostQueryBuilder
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

    /**
     * @param string|string[] $types
     * @return $this
     */
    public function whereType($types): PostQueryBuilder
    {
        if ($this->isForTypeAttachment($types) && !$this->isForStatusAny()) {
            $this->arguments[WpQueryStatus::POST_STATUS_KEY] = WpQueryStatus::INHERIT;
        }

        $this->arguments[PostType::QUERY_TYPE_KEY] = $types;

        return $this;
    }

    /**
     * @return array<string, string|int|bool|array>
     */
    protected function buildArguments(): array
    {
        $arguments = $this->arguments;
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
    private function isForTypeAttachment($types): bool
    {
        return is_array($types) ?
            Arr::searchValue($types, PostType::ATTACHMENT) : $types === PostType::ATTACHMENT;
    }

    private function setPaged(int $page)
    {
        return $this->arguments[Posts::KEY_PAGED] = max($page, Posts::FIRST_PAGE);
    }
}
