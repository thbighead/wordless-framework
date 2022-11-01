<?php

namespace Wordless\Adapters;

use WP_Post_Type;

abstract class WordlessCustomPost extends Post
{
    private WP_Post_Type $type;

    public function __construct($post, bool $with_acfs = true)
    {
        parent::__construct($post, $with_acfs);

        $this->type = get_post_type_object($this->post_type);
    }

    /**
     * @return WP_Post_Type
     */
    public function getType(): WP_Post_Type
    {
        return $this->type;
    }
}
