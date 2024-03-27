<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits;

use WP_Term;

trait MixinWpTerm
{
    private WP_Term $wpTerm;

    public static function __callStatic(string $method_name, array $arguments)
    {
        return WP_Term::$method_name(...$arguments);
    }

    public function __call(string $method_name, array $arguments)
    {
        return $this->wpTerm->$method_name(...$arguments);
    }

    public function __get(string $attribute)
    {
        return $this->wpTerm->$attribute;
    }

    public function asWpTerm(): ?WP_Term
    {
        return $this->wpTerm;
    }
}
