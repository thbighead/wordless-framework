<?php

namespace Wordless\Adapters\QueryBuilder;

use Wordless\Abstractions\Enums\WpQueryFields;
use Wordless\Abstractions\Pagination\Posts;
use Wordless\Adapters\Post;
use Wordless\Adapters\PostType;
use Wordless\Exceptions\QueryAlreadySet;
use Wordless\Helpers\Log;
use WP_Post;
use WP_Query;

class PostQueryBuilder extends QueryBuilder
{
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
     * @param string|string[] $types
     * @return $this
     */
    public function whereType($types): PostQueryBuilder
    {
        $this->arguments['post_type'] = $types;

        return $this;
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

    private function setPaged(int $page)
    {
        return $this->arguments[Posts::KEY_PAGED] = max($page, Posts::FIRST_PAGE);
    }
}
