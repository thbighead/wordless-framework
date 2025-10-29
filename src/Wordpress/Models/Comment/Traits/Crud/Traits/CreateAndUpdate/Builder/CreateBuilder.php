<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\CreateAndUpdate\Builder;

use Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\CreateAndUpdate\Builder;
use Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\CreateAndUpdate\Builder\CreateBuilder\Exceptions\WpInsertCommentFailed;

class CreateBuilder extends Builder
{
    /**
     * @return int
     * @throws WpInsertCommentFailed
     */
    public function create(): int
    {
        if (!is_int($comment_id = wp_insert_comment($arguments = $this->mountCommentArrayArguments()))) {
            throw new WpInsertCommentFailed($arguments);
        }

        return $comment_id;
    }
}
