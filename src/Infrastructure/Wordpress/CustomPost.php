<?php

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Repository;
use Wordless\Wordpress\Models\Post;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use WP_Post;

abstract class CustomPost extends Post
{
    use Register, Repository;

    public const POST_TYPE_KEY_MAX_LENGTH = 20;
    protected const TYPE_KEY = null;
    /** @var array<static, string> */
    private static array $type_keys = [];

    private PostType $type;

    /**
     * @param WP_Post|int $post
     * @param bool $with_acfs
     * @throws PostTypeNotRegistered
     */
    public function __construct(WP_Post|int $post, bool $with_acfs = true)
    {
        parent::__construct($post, $with_acfs);

        $this->type = new PostType($this->post_type);
    }

    public function getType(): PostType
    {
        return $this->type;
    }
}
