<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\QueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder;
use WP_Comment_Query;
use WP_Query;
use WP_Term_Query;
use WP_User_Query;

abstract class WpQueryBuilder extends QueryBuilder
{
    abstract protected function mountNewWpQuery(): WP_Query|WP_Term_Query|WP_Comment_Query|WP_User_Query;

    private WP_Query|WP_Term_Query|WP_Comment_Query|WP_User_Query $query;

    public function __construct()
    {
        $this->query = $this->mountNewWpQuery();
    }

    public function __clone(): void
    {
        $this->query = $this->mountNewWpQuery();
    }

    public function sql(): ?string
    {
        if (!isset($this->getQuery()->request)) {
            return null;
        }

        return $this->getQuery()->request;
    }

    protected function getQuery(): WP_User_Query|WP_Query|WP_Term_Query|WP_Comment_Query
    {
        return $this->query;
    }
}
