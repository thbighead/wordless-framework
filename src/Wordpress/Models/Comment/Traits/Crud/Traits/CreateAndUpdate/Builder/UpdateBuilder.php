<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\CreateAndUpdate\Builder;

use Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\CreateAndUpdate\Builder;
use Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\CreateAndUpdate\Builder\UpdateBuilder\Exceptions\WpUpdateCommentFailed;
use Wordless\Wordpress\Models\Post\Contracts\BasePost;
use WP_Error;
use WP_Post;

class UpdateBuilder extends Builder
{
    public function __construct(private readonly int $id, BasePost|WP_Post|int $post)
    {
        $this->post = $post;
    }

    /**
     * @return bool
     * @throws WpUpdateCommentFailed
     */
    public function update(): bool
    {
        if (($result = wp_update_comment(
                $arguments = $this->mountCommentArrayArguments(),
                true
            )) instanceof WP_Error) {
            throw new WpUpdateCommentFailed($arguments, $result);
        }

        return !($result === 0);
    }

    protected function mountCommentArrayArguments(): array
    {
        $arguments = parent::mountCommentArrayArguments();

        $arguments['comment_ID'] = $this->id;

        return $arguments;
    }
}
