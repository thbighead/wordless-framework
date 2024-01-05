<?php

namespace Wordless\Infrastructure\Wordpress\QueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;
use WP_Comment_Query;
use WP_Query;
use WP_Term_Query;
use WP_User_Query;

abstract class WpQueryBuilder extends QueryBuilder
{
    private WP_Query|WP_Term_Query|WP_Comment_Query|WP_User_Query $query;

    public function __construct(WP_Query|WP_Term_Query|WP_Comment_Query|WP_User_Query $wpQuery)
    {
        $this->query = $wpQuery;
    }

    protected function getQuery(): WP_User_Query|WP_Query|WP_Term_Query|WP_Comment_Query
    {
        return $this->query;
    }
}
