<?php

namespace Wordless\Adapters\QueryBuilder;

use Wordless\Adapters\PostType;
use Wordless\Exceptions\QueryAlreadySet;

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
        return $this->getQuery()->query($this->arguments);
    }

    public function resetQuery()
    {
        $this->query = new $this->queryClass;
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
