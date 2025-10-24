<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\MultipleUpdateBuilder\Traits;

use Wordless\Infrastructure\Wordpress\CustomPost;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\Post;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate\Builder\Exceptions\WpInsertPostError;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\MultipleUpdateBuilder\Traits\MultipleUpdateBuilder\Exceptions\CannotUpdateMultiplePostsWithSameSlug;

trait MultipleUpdateBuilder
{
    private int $actual_id;
    private readonly bool $can_update_slug;
    private readonly bool $use_same_title_to_all;
    /** @var Post[] $posts */
    private readonly array $posts;

    /**
     * @param PostModelQueryBuilder $queryBuilder
     * @param StandardType|PostType|CustomPost|string $type
     * @throws EmptyQueryBuilderArguments
     */
    public function __construct(
        protected PostModelQueryBuilder         $queryBuilder,
        StandardType|PostType|CustomPost|string $type
    )
    {
        /** @noinspection PhpMultipleClassDeclarationsInspection */
        parent::__construct(null, '', $type);

        $this->can_update_slug = count($this->posts = $this->queryBuilder->get()) <= 1;
    }

    /**
     * @param string $slug
     * @return $this
     * @throws CannotUpdateMultiplePostsWithSameSlug
     */
    public function slug(string $slug): static
    {
        if ($this->can_update_slug) {
            /** @noinspection PhpMultipleClassDeclarationsInspection */
            parent::slug($slug);
        }

        throw new CannotUpdateMultiplePostsWithSameSlug($this->posts, $slug);
    }

    public function title(string $title): static
    {
        if (!isset($this->use_same_title_to_all)) {
            $this->use_same_title_to_all = true;
        }

        /** @noinspection PhpMultipleClassDeclarationsInspection */
        return parent::title($title);
    }

    /**
     * @param bool $firing_after_events
     * @return int[]
     * @throws WpInsertPostError
     */
    public function update(bool $firing_after_events = true): array
    {
        $posts_ids = [];

        if (!isset($this->use_same_title_to_all)) {
            $this->use_same_title_to_all = false;
        }

        foreach ($this->posts as $post) {
            $this->actual_id = $post->id();

            if (!$this->use_same_title_to_all) {
                $this->title($post->post_title);
            }

            $posts_ids[] = $this->callWpInsertPost($firing_after_events);
        }

        return $posts_ids;
    }

    protected function mountPostArrayArguments(): array
    {
        /** @noinspection PhpMultipleClassDeclarationsInspection */
        $post_array = parent::mountPostArrayArguments();

        if (isset($this->actual_id) && $this->actual_id > 0) {
            $post_array['ID'] = $this->id;
        }

        return $post_array;
    }
}
