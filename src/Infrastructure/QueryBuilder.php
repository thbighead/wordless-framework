<?php

namespace Wordless\Infrastructure;

use Wordless\Exceptions\QueryAlreadySet;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

abstract class QueryBuilder
{
    protected array $arguments = [];
    protected string $queryClass;
    /** @var mixed $query */
    private $query;

    public static function fromPostEntity(string $post_type = PostType::ANY): PostQueryBuilder
    {
        return new PostQueryBuilder($post_type);
    }

    public function __construct(?string $queryClass)
    {
        $this->queryClass = $queryClass ?? get_class($this->query);
    }

    public function get()
    {
        return $this->getQuery()->query($this->buildArguments());
    }

    public function resetQuery()
    {
        $this->query = new $this->queryClass;
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

    /**
     * @param $query
     * @return $this
     * @throws QueryAlreadySet
     */
    protected function setQuery($query): QueryBuilder
    {
        if (isset($this->query)) {
            throw new QueryAlreadySet;
        }

        $this->query = $query;

        return $this;
    }
}
