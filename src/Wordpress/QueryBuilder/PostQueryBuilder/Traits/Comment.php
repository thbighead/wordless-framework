<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits;

use Wordless\Wordpress\QueryBuilder\Enums\Operator;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait Comment
{
    public function withAnyComments(): PostQueryBuilder
    {
        return $this->withComments();
    }

    /**
     * @param int $how_many
     * @param string $comparison use QueryComparisons constants to avoid errors
     * @return PostQueryBuilder
     */
    public function withComments(
        int    $how_many = 1,
        string $comparison = Operator::greater_than_or_equal->value
    ): PostQueryBuilder
    {
        $this->arguments['comment_count'] = ['compare' => $comparison, 'value' => $how_many];

        return $this;
    }

    /**
     * @param int $how_many
     * @return PostQueryBuilder
     */
    public function withDifferentThanComments(int $how_many): PostQueryBuilder
    {
        return $this->withComments($how_many, Operator::different->value);
    }

    /**
     * @param int $how_many
     * @return PostQueryBuilder
     */
    public function withLessThanComments(int $how_many): PostQueryBuilder
    {
        return $this->withComments($how_many, Operator::less_than->value);
    }

    /**
     * @param int $how_many
     * @return PostQueryBuilder
     */
    public function withLessThanOrEqualsComments(int $how_many): PostQueryBuilder
    {
        return $this->withComments($how_many, QueryComparison::LESS_THAN_OR_EQUAL);
    }

    /**
     * @param int $how_many
     * @return PostQueryBuilder
     */
    public function withMoreThanComments(int $how_many): PostQueryBuilder
    {
        return $this->withComments($how_many, QueryComparison::GREATER_THAN);
    }

    /**
     * @param int $how_many
     * @return PostQueryBuilder
     */
    public function withMoreThanOrEqualsComments(int $how_many): PostQueryBuilder
    {
        return $this->withComments($how_many);
    }

    /**
     * @return PostQueryBuilder
     */
    public function withoutComments(): PostQueryBuilder
    {
        return $this->withComments(0, QueryComparison::EQUAL);
    }
}
