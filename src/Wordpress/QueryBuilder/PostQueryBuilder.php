<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder;

use Wordless\Enums\WpQueryTaxonomy;
use Wordless\Infrastructure\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder;
use Wordless\Infrastructure\Wordpress\QueryBuilder\PostQueryBuilder\TaxonomySubQueryBuilder;
use Wordless\Infrastructure\Wordpress\QueryBuilder\WpQueryBuilder;
use Wordless\Wordpress\Models\Post\Enums\StandardStatus;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Enums\PostsListFormat;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\MetaSubQueryBuilder\Enums\Key;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Author;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Category;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Comment;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Id;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\OrderBy;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Password;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Resolver;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Search;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Slug;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Status;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Tag;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Type;
use WP_Query;

class PostQueryBuilder extends WpQueryBuilder
{
    use Author;
    use Category;
    use Comment;
    use Id;
    use OrderBy;
    use Password;
    use Resolver;
    use Search;
    use Slug;
    use Status;
    use Tag;
    use Type;

    private const KEY_IGNORE_STICKY_POSTS = 'ignore_sticky_posts';

    public function __construct(StandardType|PostType|null $postType = null)
    {
        $this->whereType($postType ?? StandardType::ANY)
            ->whereStatus(StandardStatus::reallyAny())
            ->withoutStickyPosts()
            ->deactivatePagination()
            ->setPostsFormat(PostsListFormat::all_fields);

        parent::__construct();
    }

    public function whereMeta(MetaSubQueryBuilder $subQuery): static
    {
        $this->arguments[Key::key_meta_query->value] = $subQuery;

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

    private function setPostsFormat(PostsListFormat $format): static
    {
        $this->arguments[PostsListFormat::FIELDS_KEY] = $format->value;

        return $this;
    }
}
