<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Comment\Traits;

use Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\CreateAndUpdate;
use Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\Delete;
use Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\Read;
use Wordless\Wordpress\Models\Post;
use Wordless\Wordpress\QueryBuilder\CommentQueryBuilder\CommentModelQueryBuilder;

trait Crud
{
    use CreateAndUpdate;
    use Read;
    use Delete;

    public static function query(string $from_post_model_class_namespace = Post::class): CommentModelQueryBuilder
    {
        return CommentModelQueryBuilder::make($from_post_model_class_namespace);
    }
}
