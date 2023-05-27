<?php

namespace Wordless\Infrastructure;

use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;
use WP_Query;

abstract class QueryBuilder
{
    protected array $arguments = [];
    private mixed $query;

    public static function fromPostEntity(string $post_type = PostType::ANY): PostQueryBuilder
    {
        return new PostQueryBuilder($post_type);
    }

    public function __construct(WP_Query $wpQuery)
    {
        $this->query = $wpQuery;
    }

    public function get(): array
    {
        return $this->getQuery()->query($this->buildArguments());
    }

    /**
     * @return array<string, string|int|bool|array>
     */
    protected function buildArguments(): array
    {
        return $this->arguments;
    }

    protected function getQuery()
    {
        return $this->query;
    }
}
