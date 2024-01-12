<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder;

use stdClass;
use Wordless\Application\Helpers\Arr;
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
use Wordless\Wordpress\QueryBuilder\Enums\Status;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Enums\PostsListFormat;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Exceptions\TrySetEmptyPostType;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Key;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PaginationArgumentsBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Author;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Category;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Comment;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Search;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Tag;
use WP_Post;
use WP_Query;

class PostQueryBuilder extends WpQueryBuilder
{
    use Author;
    use Category;
    use Comment;
    use OrderBy;
    use Search;
    use Tag;

    final public const KEY_NO_FOUND_ROWS = 'no_found_rows';
    final public const KEY_NO_PAGING = 'nopaging';
    private const KEY_PAGE_ID = 'page_id';
    private const KEY_PAGE_SLUG = 'pagename';
    private const KEY_POST_ID = 'p';
    private const KEY_POST_SLUG = 'name';
    private const KEY_HAS_PASSWORD = 'has_password';
    private const KEY_IGNORE_STICKY_POSTS = 'ignore_sticky_posts';
    private const KEY_POST_IN = 'post__in';
    private const KEY_POST_NOT_IN = 'post__not_in';
    private const KEY_POST_PASSWORD = 'post_password';

    /**
     * @param StandardType|PostType|null $postType
     * @throws TrySetEmptyPostType
     */
    public function __construct(StandardType|PostType|null $postType = null)
    {
        $this->whereType($postType ?? StandardType::ANY)
            ->withoutStickyPosts()
            ->deactivatePagination()
            ->setPostsFormat(PostsListFormat::all_fields);

        parent::__construct();
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
    public function onlyWithoutPassword(): static
    {
        $this->arguments[self::KEY_HAS_PASSWORD] = false;

        unset($this->arguments[self::KEY_POST_PASSWORD]);

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyWithPassword(?string $password): static
    {
        $this->arguments[self::KEY_HAS_PASSWORD] = true;

        if ($password !== null) {
            $this->arguments[self::KEY_POST_PASSWORD] = $password;
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
     * @param int $id
     * @param int ...$ids
     * @return $this
     */
    public function whereId(int $id, int ...$ids): static
    {
        if (empty($ids)) {
            $this->arguments[static::KEY_POST_ID] = $id;

            return $this;
        }

        array_unshift($ids, $id);

        $this->arguments[static::KEY_POST_IN] = $ids;

        unset($this->arguments[static::KEY_POST_NOT_IN]);

        return $this;
    }

    public function whereMeta(MetaSubQueryBuilder $subQuery): static
    {
        $this->arguments[Key::key_meta_query->value] = $subQuery;

        return $this;
    }

    /**
     * @param int $id
     * @param int ...$ids
     * @return $this
     */
    public function whereNotId(int $id, int ...$ids): static
    {
        if (empty($ids)) {
            $this->arguments[static::KEY_POST_ID] = $id;

            return $this;
        }

        array_unshift($ids, $id);

        $this->arguments[static::KEY_POST_NOT_IN] = $ids;

        unset($this->arguments[static::KEY_POST_IN]);

        return $this;
    }

    /**
     * @param string $slug
     * @param string ...$slugs
     * @return $this
     */
    public function whereSlug(string $slug, string ...$slugs): static
    {
        if (empty($slugs)) {
            $this->arguments[static::KEY_POST_SLUG] = $slug;

            return $this;
        }

        array_unshift($slugs, $slug);

        $this->arguments['post_name__in'] = $slugs;

        return $this;
    }

    public function whereStatus(Status $status): static
    {
        $this->arguments[Status::post_status_key->value] = $status->value;

        return $this;
    }

    public function whereTaxonomy(TaxonomySubQueryBuilder $subQuery): static
    {
        $this->arguments[WpQueryTaxonomy::KEY_TAXONOMY_QUERY] = $subQuery;

        return $this;
    }

    public function whereTitle(string $title): static
    {
        $this->arguments['title'] = $title;

        return $this;
    }

    /**
     * @param StandardType|PostType|string $type
     * @param StandardType|PostType|string ...$types
     * @return $this
     */
    public function whereType(
        StandardType|PostType|string $type,
        StandardType|PostType|string ...$types
    ): static
    {
        $full_types = [$this->retrieveTypeAsString($type)];

        foreach ($types as $type) {
            $full_types[] = $this->retrieveTypeAsString($type);
        }

        $this->arguments[PostType::QUERY_TYPE_KEY] = $full_types;

        return $this;
    }

    private function retrieveTypeAsString(StandardType|PostType|string $type): string
    {
        if ($type instanceof StandardType) {
            return $type->name;
        }

        if ($type instanceof PostType) {
            return $type->name;
        }

        return $type;
    }

    public function withStickyPosts(): static
    {
        $this->arguments[self::KEY_IGNORE_STICKY_POSTS] = false;

        return $this;
    }

    public function withoutStickyPosts(): static
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
        $this->fixArguments();

        $arguments = $this->arguments;

        $metaSubQueryBuilder = $this->arguments[Key::key_meta_query->value] ?? null;
        if ($metaSubQueryBuilder instanceof MetaSubQueryBuilder) {
            $arguments[Key::key_meta_query->value] = $metaSubQueryBuilder->build();
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

    protected function fixArguments(): void
    {
        if ($this->isForTypeAttachment(...$this->arguments[PostType::QUERY_TYPE_KEY]) &&
            !($this->arguments[Status::post_status_key->value] === Status::any->value ||
                $this->arguments[Status::post_status_key->value] === Status::inherit->value)) {
            $this->arguments[Status::post_status_key->value] = Status::inherit;
        }
    }

    /**
     * @return WP_Query
     */
    protected function getQuery(): WP_Query
    {
        return parent::getQuery();
    }

    protected function mountNewWpQuery(): WP_Query
    {
        return new WP_Query;
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
     * @param StandardType|PostType|string $type
     * @param StandardType|PostType|string ...$types
     * @return bool
     */
    private function isForTypeAttachment(
        StandardType|PostType|string $type,
        StandardType|PostType|string ...$types
    ): bool
    {
        if ($this->isTypeAttachment($type)) {
            return true;
        }

        foreach ($types as $type) {
            if ($this->isTypeAttachment($type)) {
                return true;
            }
        }

        return false;
    }

    private function isTypeAttachment(StandardType|PostType|string $type): bool
    {
        if ($type instanceof StandardType) {
            return $type === StandardType::attachment;
        }

        if ($type instanceof PostType) {
            return $type->is(StandardType::attachment->name);
        }

        return $type === StandardType::attachment->name;
    }

    private function query(array $extra_arguments = []): array
    {
        $this->resolveSearch();

        return $this->getQuery()->query($this->buildArguments($extra_arguments));
    }

    private function setPostsFormat(PostsListFormat $format): static
    {
        $this->arguments[PostsListFormat::FIELDS_KEY] = $format->value;

        return $this;
    }
}
