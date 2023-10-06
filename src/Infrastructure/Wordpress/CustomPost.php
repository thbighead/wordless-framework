<?php

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Infrastructure\Wordpress\CustomPost\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Repository;
use Wordless\Wordpress\Models\Post;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use WP_Post;

abstract class CustomPost extends Post
{
    use Register;
    use Repository;

    protected const TYPE_KEY = null;

    private PostType $type;

    /**
     * @param WP_Post|int $post
     * @param bool $with_acfs
     * @throws InitializingModelWithWrongPostType
     * @throws PostTypeNotRegistered
     */
    public function __construct(WP_Post|int $post, bool $with_acfs = true)
    {
        parent::__construct($post, $with_acfs);

        $this->type = new PostType($this->post_type);

        if (!$this->type->is(static::TYPE_KEY)) {
            throw new InitializingModelWithWrongPostType($this, $with_acfs);
        }
    }

    public function getType(): PostType
    {
        return $this->type;
    }
}
