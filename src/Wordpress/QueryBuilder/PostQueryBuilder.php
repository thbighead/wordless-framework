<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder;

use Wordless\Enums\WpQueryTaxonomy;
use Wordless\Infrastructure\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder;
use Wordless\Infrastructure\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder;
use Wordless\Infrastructure\Wordpress\QueryBuilder\WpQueryBuilder;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\Pagination\Posts;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Enums\PostsListFormat;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Key;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Author;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Category;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Comment;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Search;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Status;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Tag;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\WhereId;
use WP_Query;

class PostQueryBuilder extends WpQueryBuilder
{
    use Author;
    use Category;
    use Comment;
    use OrderBy;
    use Resolver;
    use Search;
    use Status;
    use Tag;
    use WhereId;

    final public const KEY_NO_FOUND_ROWS = 'no_found_rows';
    final public const KEY_NO_PAGING = 'nopaging';
    private const KEY_PAGE_SLUG = 'pagename';
    private const KEY_POST_SLUG = 'name';
    private const KEY_HAS_PASSWORD = 'has_password';
    private const KEY_IGNORE_STICKY_POSTS = 'ignore_sticky_posts';
    private const KEY_POST_PASSWORD = 'post_password';

    public function __construct(StandardType|PostType|null $postType = null)
    {
        $this->whereType($postType ?? StandardType::ANY)
            ->withoutStickyPosts()
            ->deactivatePagination()
            ->setPostsFormat(PostsListFormat::all_fields);

        parent::__construct();
    }

    public function onlyWithoutPassword(): static
    {
        $this->arguments[self::KEY_HAS_PASSWORD] = false;

        unset($this->arguments[self::KEY_POST_PASSWORD]);

        return $this;
    }

    public function onlyWithPassword(?string $password): static
    {
        $this->arguments[self::KEY_HAS_PASSWORD] = true;

        if ($password !== null) {
            $this->arguments[self::KEY_POST_PASSWORD] = $password;
        }

        return $this;
    }

    public function whereMeta(MetaSubQueryBuilder $subQuery): static
    {
        $this->arguments[Key::key_meta_query->value] = $subQuery;

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
    public function whereType(StandardType|PostType|string $type, StandardType|PostType|string ...$types): static
    {
        if (!isset($this->arguments[PostType::QUERY_TYPE_KEY])) {
            $this->arguments[PostType::QUERY_TYPE_KEY] = [];
        }

        array_unshift($types, $type);

        foreach ($types as $type) {
            $this->arguments[PostType::QUERY_TYPE_KEY][] = $this->retrieveTypeAsString($type);
        }

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

    private function isForTypeAttachment(): bool
    {
        if (!isset($this->arguments[PostType::QUERY_TYPE_KEY])) {
            return false;
        }

        foreach ($this->arguments[PostType::QUERY_TYPE_KEY] as $type) {
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
