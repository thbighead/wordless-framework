<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Comment\Traits;

use Wordless\Wordpress\Models\Comment\Traits\MixinWpComment\Exceptions\InvalidAttribute;
use WP_Comment;

trait MixinWpComment
{
    private WP_Comment $wpComment;

    public static function __callStatic(string $method_name, array $arguments)
    {
        return WP_Comment::$method_name(...$arguments);
    }

    public function __call(string $method_name, array $arguments)
    {
        return $this->wpComment->$method_name(...$arguments);
    }

    /**
     * @param string $attribute
     * @return mixed
     * @throws InvalidAttribute
     */
    public function __get(string $attribute)
    {
        if (!property_exists($this->wpComment, $attribute)) {
            throw new InvalidAttribute($attribute);
        }

        return $this->wpComment->$attribute;
    }

    public function asWpComment(): WP_Comment
    {
        return $this->wpComment;
    }
}
